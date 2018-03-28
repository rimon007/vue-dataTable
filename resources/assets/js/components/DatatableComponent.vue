<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">DataTable Component</div>

                    <div class="card-body">
                        <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="example_length">
                                        <label>Show
                                            <select name="example_length" 
                                                v-model="limit"
                                                class="form-control form-control-sm"
                                                @change="toPage(page)">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select> entries</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div id="example_filter" class="dataTables_filter">
                                        <label>Search:
                                            <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example">
                                        </label>
                                    </div>
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

                                                <!-- <th class="sorting_desc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 121px;">Name</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 195px;">Position</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 90px;">Office</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 35px;">Age</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 77px;">Start date</th>
                                                <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 69px;">Salary</th> -->
                                            </tr>
                                        </thead>
                                        <tbody :class="(loading) ? 'loader' : ''">
                                            <tr v-for="item in items">
                                                <slot :row="item">
                                                    <td v-for="column in columns" 
                                                        v-if="hasValue(item, column)">
                                                        {{ itemValue(item, column) }}
                                                    </td>
                                                    <td>
                                                        <slot name="btn-show" :itemData="item" @handleevent="handleAction('showss', item)">
                                                            <button 
                                                                v-show="btnAction && btnAction.show"
                                                                type="button" 
                                                                class="btn btn-outline-info btn-sm"
                                                                title="show details"
                                                                @click="handleAction('show', item)">
                                                                <i class="fa fa-search-plus"></i>        
                                                            </button>
                                                        </slot>
                                                        
                                                        <slot name="btn-edit" :itemData="item">
                                                            <button 
                                                                v-show="btnAction && btnAction.edit"
                                                                type="button" 
                                                                class="btn btn-outline-primary btn-sm"
                                                                title="Edit"
                                                                @click="handleAction('edit', item)">
                                                                <i class="fa fa-edit"></i>        
                                                            </button>
                                                        </slot>
                                                        
                                                        <slot name="btn-delete" :itemData="item">
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
                            
                            <pagination :meta="meta"></pagination>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import pagination from './Pagination'

    export default {
        props: {
            columns: {
                type: [Object, Array],
                required: true
            },
            data: {
                required: true
            },
            url: {
                default: window.location.href
            },
            btnAction: {
                default: false
            }

        },
        mounted() {
            setTimeout(() => this.pushItemOrMetaData(this.data), 500)
        },
        data() {
            return {                
                search: '',
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
        components: {
            pagination
        },
        // computed: {
        //     tblHeaders() {
        //         return this.columns.map((column) => {
        //             let header = []
        //             header['sort'] = (!('sort' in column)) ? true : column.sort
        //             header['field'] = (!('field' in column)) ? false : column.field
        //             header['label'] = column.label
        //             return header
        //         })
        //     }
        // },
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
                if('data' in response && response.data.length > 0) {
                    self.items = response.data;
                    self.meta.total = response.total;
                    self.meta.per_page = response.per_page;
                    self.meta.current_page = response.current_page;
                    self.meta.last_page = response.last_page;
                    self.meta.next_page_url  = response.next_page_url;
                    self.meta.prev_page_url  = response.prev_page_url;
                    self.meta.from  = response.from;
                    self.meta.to   = response.to;
                }
            },
            
            toPage(page) {
                this.loading = true
                this.page = page
                axios.get(`${this.url}?page=${this.page}&orderColumn=${this.sorting['sort_column']}&orderBy=${this.sorting['sort_by']}&limit=${this.limit}`).then(response => {
                    this.loading = false
                    this.pushItemOrMetaData(response.data)
                });
            },

            sortByColumn(column) {
                console.log(column)
                if(column.sort === true) {
                    this.sorting['sort_column'] = column.field
                    if(this.sorting['sort_by'] == 'desc') {
                        this.sorting['sort_by'] = 'asc'
                        this.sorting['sort_class'] = 'sorting_asc'
                    } else {
                        this.sorting['sort_class'] = 'sorting_desc'
                        this.sorting['sort_by'] = 'desc'
                    }
                    this.toPage(this.page)                    
                }
            },

            handleAction(actionType, item, url = false) {
                alert(actionType)
            }
        }
    }
</script>

<style scope>
    table.dataTable{clear:both;margin-top:6px !important;margin-bottom:6px !important;max-width:none !important;border-collapse:separate !important}table.dataTable td,table.dataTable th{-webkit-box-sizing:content-box;box-sizing:content-box}table.dataTable td.dataTables_empty,table.dataTable th.dataTables_empty{text-align:center}table.dataTable.nowrap th,table.dataTable.nowrap td{white-space:nowrap}div.dataTables_wrapper div.dataTables_length label{font-weight:normal;text-align:left;white-space:nowrap}div.dataTables_wrapper div.dataTables_length select{width:75px;display:inline-block}div.dataTables_wrapper div.dataTables_filter{text-align:right}div.dataTables_wrapper div.dataTables_filter label{font-weight:normal;white-space:nowrap;text-align:left}div.dataTables_wrapper div.dataTables_filter input{margin-left:0.5em;display:inline-block;width:auto}div.dataTables_wrapper div.dataTables_info{padding-top:0.85em;white-space:nowrap}div.dataTables_wrapper div.dataTables_paginate{margin:0;white-space:nowrap;text-align:right}div.dataTables_wrapper div.dataTables_paginate ul.pagination{margin:2px 0;white-space:nowrap;justify-content:flex-end}div.dataTables_wrapper div.dataTables_processing{position:absolute;top:50%;left:50%;width:200px;margin-left:-100px;margin-top:-26px;text-align:center;padding:1em 0}table.dataTable thead>tr>th.sorting_asc,table.dataTable thead>tr>th.sorting_desc,table.dataTable thead>tr>th.sorting,table.dataTable thead>tr>td.sorting_asc,table.dataTable thead>tr>td.sorting_desc,table.dataTable thead>tr>td.sorting{padding-right:30px}table.dataTable thead>tr>th:active,table.dataTable thead>tr>td:active{outline:none}table.dataTable thead .sorting,table.dataTable thead .sorting_asc,table.dataTable thead .sorting_desc,table.dataTable thead .sorting_asc_disabled,table.dataTable thead .sorting_desc_disabled{cursor:pointer;position:relative}table.dataTable thead .sorting:before,table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:before,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:before,table.dataTable thead .sorting_desc:after,table.dataTable thead .sorting_asc_disabled:before,table.dataTable thead .sorting_asc_disabled:after,table.dataTable thead .sorting_desc_disabled:before,table.dataTable thead .sorting_desc_disabled:after{position:absolute;bottom:0.9em;display:block;opacity:0.3}table.dataTable thead .sorting:before,table.dataTable thead .sorting_asc:before,table.dataTable thead .sorting_desc:before,table.dataTable thead .sorting_asc_disabled:before,table.dataTable thead .sorting_desc_disabled:before{right:1em;content:"\2191"}table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after,table.dataTable thead .sorting_asc_disabled:after,table.dataTable thead .sorting_desc_disabled:after{right:0.5em;content:"\2193"}table.dataTable thead .sorting_asc:before,table.dataTable thead .sorting_desc:after{opacity:1}table.dataTable thead .sorting_asc_disabled:before,table.dataTable thead .sorting_desc_disabled:after{opacity:0}div.dataTables_scrollHead table.dataTable{margin-bottom:0 !important}div.dataTables_scrollBody table{border-top:none;margin-top:0 !important;margin-bottom:0 !important}div.dataTables_scrollBody table thead .sorting:after,div.dataTables_scrollBody table thead .sorting_asc:after,div.dataTables_scrollBody table thead .sorting_desc:after{display:none}div.dataTables_scrollBody table tbody tr:first-child th,div.dataTables_scrollBody table tbody tr:first-child td{border-top:none}div.dataTables_scrollFoot>.dataTables_scrollFootInner{box-sizing:content-box}div.dataTables_scrollFoot>.dataTables_scrollFootInner>table{margin-top:0 !important;border-top:none}@media screen and (max-width: 767px){div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{text-align:center}}table.dataTable.table-sm>thead>tr>th{padding-right:20px}table.dataTable.table-sm .sorting:before,table.dataTable.table-sm .sorting_asc:before,table.dataTable.table-sm .sorting_desc:before{top:5px;right:0.85em}table.dataTable.table-sm .sorting:after,table.dataTable.table-sm .sorting_asc:after,table.dataTable.table-sm .sorting_desc:after{top:5px}table.table-bordered.dataTable th,table.table-bordered.dataTable td{border-left-width:0}table.table-bordered.dataTable th:last-child,table.table-bordered.dataTable th:last-child,table.table-bordered.dataTable td:last-child,table.table-bordered.dataTable td:last-child{border-right-width:0}table.table-bordered.dataTable tbody th,table.table-bordered.dataTable tbody td{border-bottom-width:0}div.dataTables_scrollHead table.table-bordered{border-bottom-width:0}div.table-responsive>div.dataTables_wrapper>div.row{margin:0}div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:first-child{padding-left:0}div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child{padding-right:0}

    .loader {
        color: transparent !important;
        pointer-events: none;
        position: relative;
    }

    .loader:after {
        animation: spinAround 500ms infinite linear;
        border: 2px solid #dbdbdb;
        border-radius: 290486px;
        border-right-color: transparent;
        border-top-color: transparent;
        content: "";
        display: block;
        width: 1em;
        height: 1em;
        position: relative;
        position: absolute;
        left: calc(50% - (1em / 2));
        top: calc(50% - (1em / 2));
        position: absolute !important;
    }

    @keyframes spinAround {
        from {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        
        to {
            -webkit-transform: rotate(359deg);
            transform: rotate(359deg);
        }
    }
</style>
