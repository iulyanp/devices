<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body>

        <div id="app" class="container">
            <div class="title m-b-md">
                Home Devices
            </div>
            <div class="row">
                <div class="col-md-3" v-for="(device, idx) in devices">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">@{{ device.id }}. @{{ device.name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted"><span class="badge badge-info">@{{ device.unit }}</span></h6>

                            <a @click="showDevice(device)" data-toggle="modal" data-target="#exampleModal" class="card-link"><i class="fa fa-eye"></i> See details</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="exampleModal" role="dialog">
                <div class="modal-dialog" v-if="device" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@{{ device.id }}. @{{ device.name }} <span class="badge badge-info">@{{ device.unit }}</span></h5>
                            <button type="button" @click="clear" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h3>Values</h3>
                            <ul>
                                <li v-for="value in device.values">@{{ value }}</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="clear" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script src="//{{ Request::getHost() }}:3000/socket.io/socket.io.js"></script>
        <script src="{{ mix('js/app.js') }}"></script>

        <script>
            var socket = io('//localhost:3000');

            new Vue({
                el: '#app',

                data: {
                    'devices': [],
                    'device': ''
                },

                created: function () {
                    axios.get('/api/devices')
                         .then(function (response) {
                             this.devices = response.data;
                         }.bind(this))
                         .catch(function (error) {
                             console.log(error);
                         });

                    socket.on('update-device', function (device) {
                        this.updateDevice(device);
                    }.bind(this));

                    socket.on('create-device', function (device) {
                        this.addDevice(device);
                    }.bind(this));

                    socket.on('delete-device', function (device) {
                        this.removeDevice(device);
                    }.bind(this));
                },

                methods: {
                    showDevice: function(device) {
                        axios.get('/api/devices/' + device.id)
                             .then(function (response) {
                                 this.device = response.data;
                             }.bind(this))
                             .catch(function (error) {
                                 console.log(error);
                             });
                    },
                    addDevice: function (device) {
                        this.devices.push(device);
                    },
                    updateDevice: function (device) {
                        let index = this.devices.findIndex(item => item.id === device.id);

                        this.$set(this.devices, index, device);
                    },
                    removeDevice: function (device) {
                        let index = this.devices.findIndex(item => item.id === device.id);

                        this.devices.splice(index, 1);
                    },
                    clear: function() {
                        this.device = '';
                    }
                }
            });
        </script>
    </body>
</html>
