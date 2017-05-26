<!DOCTYPE html>
<html>
    <header>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.3/vue.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.3.3"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <style>
            [v-cloak] {
              display: none;
            }
        </style>
    </header>

    <body>
        <div id="reorgApp" class="container-fluid">
            <div class="page-header">
              <h1>Reorg Portal</h1>
            </div>

            <div class="row form-group">
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Physician First Name" v-model="first_name"/>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" @click="search">Search</button>
                        </span>
                    </div>
                </div>
                <div class="col-sm-1 col-sm-offset-8" v-if="results" v-cloak>
                    <button class="btn btn-default" type="button" @click="download">Download</button>
                </div>
            </div>

            <div class="row" v-if="results" v-cloak>
                <div class="col-lg-12">
                    <table class="table table-hover">
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Field</th>
                            <th>State</th>
                            <th>Date of Payment</th>
                            <th>Amount</th>
                        </tr>

                        <tr v-for="physician in results">
                            <td>@{{physician.Physician_First_Name}}</td>
                            <td>@{{physician.Physician_Last_Name}}</td>
                            <td>@{{physician.Physician_Specialty}}</td>
                            <td>@{{physician.Physician_License_State_code1}}</td>
                            <td>@{{physician.Date_of_Payment}}</td>
                            <td>@{{physician.Total_Amount_of_Payment_USDollars}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>

    <script>
        var app = new Vue({
            el: '#reorgApp',
            data: {
                results: null,
                first_name: null
            },
            methods: {
                search: function() {
                    this.$http.get('/search?first_name=' + this.first_name).then(response => {
                        this.results = response.body;
                    });
                },
                download: function() {
                    var id_array = this.results.map(x => { return x.Id; });

                    var url = '/download?' + $.param({'ids': id_array});
                    window.location = url;
                }
            }
        });
    </script>
</html>