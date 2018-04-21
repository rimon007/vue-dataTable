<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Server side dataTable</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link href="/css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div id="app">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card card-default">
                            <div class="card-header">DataTable Component</div>

                            <div class="card-body">
                                <v-datatable
                                    :columns="{{ json_encode([
                                        ['label' => 'ID', 'field' => 'id', 'sort' => true],
                                        ['label' => 'Admission Roll', 'field' => 'admission_roll', 'sort' => true],
                                        ['label' => 'Name', 'field' => 'applicants_name', 'sort' => true],
                                        ['label' => 'Roll', 'field' => 'roll', 'sort' => true],
                                        ['label' => 'Group', 'field' => 'group'],
                                        ['label' => 'Action'],
                                    ]) }}"
                                    :data="{{ json_encode($data) }}"
                                    :btn-action="{{ json_encode(['show' => true,'edit' => true,'delete' => true,]) }}"
                                    search-columns="id, admission_roll, applicants_name, roll, group"
                                    @search="handleSearchByOwn"
                                    @action="handleActionByOwn"
                                >
                                    <!-- <template slot="search-box" slot-scope="props">
                                        <div class="form-inline pull-right">
                                            <select class="form-control mb-2 mr-sm-2 mb-sm-0" @change="props.handleSearch(false)">
                                                <option value="" disabled="" selected="">choose</option>
                                                <option value="Md. Mithon Ali">Md. Mithon Ali</option>
                                                <option value="Md. Dulal Hossain">Md. Dulal Hossain</option>
                                                <option value="Mst. Jannaton Moni">Mst. Jannaton Moni</option>
                                                <option value="Rima">Rima</option>
                                            </select>   
                                            <select class="form-control mb-2 mr-sm-2 mb-sm-0" @change="props.handleSearch">
                                                <option value="" disabled="" selected="">choose</option>
                                                <option value="131421">131421</option>
                                                <option value="131251">131251</option>
                                                <option value="325601">325601</option>
                                                <option value="131473">131473</option>
                                            </select>                                               
                                        </div>
                                    </template> -->
                                    <template slot="btn-show" slot-scope="props">
                                        <button 
                                            type="button" 
                                            class="btn btn-outline-success btn-sm"
                                            title="Delete"
                                            @click="props.handleAction('show', props.data, false)">
                                            <i class="fa fa-info"></i>        
                                        </button>
                                    </template>    
                                </v-datatable>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/js/app.js"></script>
    </body>
</html>