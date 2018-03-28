<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link href="/css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div id="app">
            <datatable-component
                :columns="{{ json_encode([
                    ['label' => 'ID', 'field' => 'id', 'sort' => true],
                    ['label' => 'Name', 'field' => 'name', 'sort' => true],
                    ['label' => 'Price', 'field' => 'price', 'sort' => true],
                    ['label' => 'Description', 'field' => 'description'],
                    ['label' => 'Start Date', 'field' => 'created_at'],
                    ['label' => 'Updated AT', 'field' => 'updated_at', 'sort' => true],
                    ['label' => 'Action'],
                ]) }}"
                :data="{{ json_encode($data) }}"
                :btn-action="{{ json_encode(['show' => true,'edit' => true,'delete' => true,]) }}">
                 <template slot="btn-show" slot-scope="{ itemData }">
                    <button 
                        type="button" 
                        class="btn btn-outline-success btn-sm"
                        title="Delete"
                        @click="$emit('handleevent')">
                        <i class="fa fa-info"></i>        
                    </button>
                </template>    
                </datatable-component>
        </div>
        <script src="/js/app.js"></script>
    </body>
</html>
