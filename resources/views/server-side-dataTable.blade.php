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
            <datatable-component
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
            >
                <template slot="btn-show" slot-scope="props">
                    <button 
                        type="button" 
                        class="btn btn-outline-success btn-sm"
                        title="Delete"
                        @click="props.handleevent('show', props.itemData)">
                        <i class="fa fa-info"></i>        
                    </button>
                </template>    
            </datatable-component>
        </div>
        <script src="/js/app.js"></script>
    </body>
</html>