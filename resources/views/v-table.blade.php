<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Table with pagination</title>

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
                            <div class="card-header">Common table with paginate & also for normal</div>
                            <?php 
                                $faker = Faker\Factory::create();
                                // $data = [];
                                // for($i = 0; $i <= 50; $i++) {
                                //     $data[] = [
                                //         'id' => $faker->randomDigit,
                                //         'name' => $faker->name,
                                //         'price' => $faker->numberBetween(100, 5000),
                                //         'multiple_price' => $faker->numberBetween(100, 1000),
                                //         'status' => '--',
                                //     ];
                                // }
                            ?>
                            <div class="card-body">
                               <v-table
                                    headers="id, admission_roll, applicants_name, roll, group, " 
                                    fields="id, admission_roll, applicants_name, roll, group"
                                    :data="{{ json_encode($data) }}"
                                    @action="handleActionByOwn"
                                >
                                    <template slot="btn-action" slot-scope="props">
                                        <td>
                                            <button 
                                                type="button" 
                                                class="btn btn-outline-success btn-sm"
                                                title="Delete"
                                                @click="props.handleAction('show', props.data)">
                                                <i class="fa fa-info"></i>        
                                            </button>                                            
                                        </td>
                                    </template>   
                                </v-table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/js/app.js"></script>
    </body>
</html>