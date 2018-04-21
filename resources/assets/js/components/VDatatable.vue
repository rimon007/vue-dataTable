<template>     
    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="example_length">
                    <label>Show
                        <select name="example_length" 
                            v-model="limit"
                            class="form-control form-control-sm"
                            @change="handleChangeLimit">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select> entries</label>
                </div>
            </div>
            <div class="col-sm-12 col-md-6" v-show="searchBoxIsVisible">
                <slot name="search-box" :handleSearch="hasOwnSearchHandler">   
                    <div id="example_filter" class="dataTables_filter">
                        <label>Search:
                            <input type="text" 
                                @keyup="handleSearch" 
                                class="form-control form-control-sm" 
                                placeholder="search" 
                                aria-controls="example">
                        </label>
                    </div>                    
                </slot> 
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table id="example" class="table table-striped table-bordered dataTable" style="width: 100%;" role="grid" aria-describedby="example_info">
                    <thead>
                        <tr role="row">
                            <slot name="columns">
                                <th v-for="column in columns" 
                                    :class="(column.sort) ? (sorting.sort_column == column.field) ? sorting.sort_class : ' sorting' : ''"
                                    @click="sortByColumn(column)"
                                >
                                    {{ column.label }}
                                </th>
                            </slot>
                        </tr>
                    </thead>
                    <tbody :class="(loading) ? 'loader' : ''">
                        <tr v-for="item in items">
                            <slot :row="item">
                                <td v-for="column in columns" 
                                    v-if="hasValue(item, column)">
                                    {{ itemValue(item, column) }}
                                </td>
                                <td v-show="btnAction">
                                    <slot name="btn-show" :data="item" :handleAction="handleAction">
                                        <button 
                                            v-show="btnAction && btnAction.show"
                                            type="button" 
                                            class="btn btn-outline-info btn-sm"
                                            title="show details"
                                            @click="handleAction('show', item)">
                                            <i class="fa fa-search-plus"></i>        
                                        </button>
                                    </slot>
                                    
                                    <slot name="btn-edit" :data="item">
                                        <button 
                                            v-show="btnAction && btnAction.edit"
                                            type="button" 
                                            class="btn btn-outline-primary btn-sm"
                                            title="Edit"
                                            @click="handleAction('edit', item)">
                                            <i class="fa fa-edit"></i>        
                                        </button>
                                    </slot>
                                    
                                    <slot name="btn-delete" :data="item">
                                        <button 
                                            v-show="btnAction && btnAction.delete"
                                            type="button" 
                                            class="btn btn-outline-danger btn-sm"
                                            title="Delete"
                                            @click="handleAction('delete', item)">
                                            <i class="fa fa-trash"></i>        
                                        </button>
                                    </slot>
                                </td>
                            </slot>
                        </tr>
                        <tr v-if="items.length === 0">
                            <td :colspan="columns.length" align="center"><strong>No Record Found</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <pagination :meta="meta" @changed="fetch"></pagination>                            
    </div>
</template>

<script>
    export default {
        props: {
            columns: {
                type: [Object, Array],
                required: true
            },
            data: { required: true },
            url: { default: window.location.href },
            btnAction: { default: false },
            searchBox: { default: true },
            searchColumns: { default: false }
        },
        mounted() {
            if(this.searchColumns === false) {
                this.dbSearchColumns = this.columns.map(column => column.field).filter(col => col !== undefined).join(',')
            } else {
                this.dbSearchColumns = this.searchColumns
            }
            setTimeout(() => this.pushItemOrMetaData(this.data), 500)
        },
        data() {
            return {                
                searchQuery: '',
                dbSearchColumns: '',
                loading: false,
                items: [],
                limit: 10,
                page: 0,
                sorting: {
                    sort_class: '',
                    sort_column: '',
                    sort_by: ''
                },
                meta: {
                    total: 0,
                    per_page: 0,
                    current_page: 0,
                    last_page: 0,
                    next_page_url: 0,
                    prev_page_url: 0,
                    from: 0,
                    to: 0                     
                }   
            }
        },
        computed: {
            /*tblHeaders() {
                return this.columns.map((column) => {
                    let header = []
                    header['sort'] = (!('sort' in column)) ? true : column.sort
                    header['field'] = (!('field' in column)) ? false : column.field
                    header['label'] = column.label
                    return header
                })
            }*/

            searchBoxIsVisible() {
                return JSON.parse(this.searchBox)
            }
        },
        methods: {
            hasValue (item, column) {
                return item[column.field] !== undefined
            },

            itemValue (item, column) {
                //column.toLowerCase()
                return _(item[column.field]).truncate(25)
            },

            pushItemOrMetaData(response) {
                const self = this;  
                self.items = response.data;
                self.meta.total = response.total;
                self.meta.per_page = response.per_page;
                self.meta.current_page = response.current_page;
                self.meta.last_page = response.last_page;
                self.meta.next_page_url  = response.next_page_url;
                self.meta.prev_page_url  = response.prev_page_url;
                self.meta.from  = response.from;
                self.meta.to   = response.to;                
                self.limit = self.meta.per_page
                //if('data' in response && response.data.length > 0) {}
            },
            
            fetch(page) {
                this.loading = true
                this.page = page
                let requestParam = {
                    page: this.page,
                    limit: this.limit,
                }
                if(this.sorting['sort_column'] !== '') {
                    requestParam['orderColumn'] = this.sorting['sort_column']
                    requestParam['orderBy'] = this.sorting['sort_by']
                }
                if(this.searchQuery !== '') {
                    requestParam['searchColumns'] = this.dbSearchColumns.replace(/\s/g, "")
                    requestParam['query'] = this.searchQuery
                }

                axios.get(`${this.url}`, {params: requestParam}).then(response => {
                    this.loading = false
                    this.pushItemOrMetaData(response.data)
                });
            },

            sortByColumn(column) {
                if(column.sort === true) {
                    this.sorting['sort_column'] = column.field
                    if(this.sorting['sort_by'] == 'desc') {
                        this.sorting['sort_by'] = 'asc'
                        this.sorting['sort_class'] = 'sorting_asc'
                    } else {
                        this.sorting['sort_class'] = 'sorting_desc'
                        this.sorting['sort_by'] = 'desc'
                    }
                    this.fetch(this.page)                    
                }
            },

            handleAction(...params) {
                let [actionType, data, ownHandler] = params
                console.log(actionType, data, ownHandler)
                if(ownHandler == false) {
                    return this.$emit('action', [actionType, data])
                }
                alert('add you own template for action type : '+ actionType)
            },

            handleChangeLimit() {
                let totalPage = Math.round(this.meta.total/this.limit)
                if(totalPage < this.page) {
                    this.fetch(totalPage)
                }
                this.fetch(this.page)
            },

            handleSearch: _.debounce( function(e) {
                let inputVal = e.target.value               
                if((this.searchQuery == '' || this.searchQuery === inputVal) && (inputVal.length <= 0 || this.searchQuery === inputVal || inputVal.replace(/\s/g, "") == ''))
                    return;

                this.searchQuery = inputVal
                this.fetch(1)   
            }, 500),

            hasOwnSearchHandler(slug) {
                if(slug !== false) {
                    return this.handleSearch(event)
                }
                this.$emit('search')
            }
        }
    }
</script>
