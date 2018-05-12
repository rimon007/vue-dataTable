<template>
	<div>
		<table class="table"
			   :class="tblClass">
		    <thead>
                <slot name="thead">
	        		<tr>
	                    <th v-for="column in tblHeaders" v-text="column"></th>
			        </tr>
                </slot>
		    </thead>
		    <tbody :class="loading ? 'loader' : ''">
                <tr v-for="item in getItems">
                    <slot :row="item">
                        <td v-for="column in itemFields" 
                            v-if="hasValue(item, column)">
                            {{ itemValue(item, column) }}
                        </td>
                        <slot name="btn-action" :data="item" :handleAction="handleAction"></slot>
                    </slot>
                </tr>
                <tr v-if="getItems.length === 0">
                	<td :colspan="tblHeaders.length" align="center">No Record Found!</td>
                </tr>
		    </tbody>
		</table>
        <pagination :meta="meta" @changed="fetch"></pagination> 
	</div>
</template>

<script>
	export default {
		props: {
			tblClass: { default: 'table-striped table-bordered' },			
            headers: { type: String },
            fields: { type: String },
            data: { required: true },
		},
		mounted() {
			this.dataItems = this.data
		},
		watch: {
			data() {
				this.dataItems = this.data
			}
		},
		data() {			
            return {
            	loading: false,
	            items: [],
	            dataItems: [],
	            page: 0,
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
			tblHeaders() {
				return this.headers.split(',').map(label => label.replace(/\s+/, "").replace(/\b\w/g, l => l.toUpperCase()));
			},
			itemFields() {
				return this.fields.replace(/\s+/g, "").split(',')
			},
			getItems() {
				this.pushItemOrMetaData(this.dataItems)
				return this.items
			}
		},
        methods: {
            hasValue (item, column) {
            	let relation = this.hasRelation(column)
            	if(relation) {
            		var [column] = relation
            	}
                return item[column] !== undefined
            },

            itemValue (item, column) {
            	if(column.includes(".")) {
            		let [colName, ...fields] = column.split('.')
            		return item[colName][fields.join('.')]
            	}
                return item[column]
            },

            hasRelation(str) {
            	if(str.includes(".")) {
            		return str.split('.')
            	}
            	return false
            },

            pushItemOrMetaData(response) {
                const self = this;  
                if(response !== undefined && response !== null && response !== '') {
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
		                self.limit = self.meta.per_page                	
	                } else if(response.length) {
	                	self.items = response;
	                }	
                }
            },
            
            fetch(page) {
                this.loading = true
                this.page = page
				history.pushState(null, null, `?page=${page}`)
                axios.get(`?page=${this.page}`).then(response => {
                    this.loading = false
                    this.dataItems = response.data
					//this.pushItemOrMetaData(response.data)
                });
            },

            handleAction(...params) {
                return this.$emit('action', [...params])
            },

        }
	}
</script>