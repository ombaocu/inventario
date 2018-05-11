app.controller('VehiculoController',
    ['$http', '$scope', '$aside', 'Upload', '$timeout','$alert','$animate', 'ErrorService', 'CrudService', '$routeParams',
        function ($http, $scope, $aside, Upload, $timeout, $alert, $animate, ErrorService, CrudService, $routeParams) {

            $scope.settings = {
                singular: 'Vehículo',
                plural: 'Vehículos',
                cmd: 'Adicionar'
            };

            $scope.AppPath = {
                baseUrl : 'http://localhost/sgtcl/',
                assets : 'assets/'
            };

            loadData();

            $scope.tipoVehiculo = 'ligero';


            var errorAlert = $alert({
                title: 'Alerta',
                content: '<br>Error por favor revise los datos que has entrado. Si el error continua por favor contacte al administrador del sistema.',
                placement: 'top',
                type: 'theme',
                show: false,
                //template: 'assets/tpl/partials/alert-introduction.html',
                animation: 'mat-grow-top-right'
            });

            $scope.year = (new Date()).getFullYear();

            $scope.ChangeTipoVehiculo = function (tipo) {
                $scope.formErrors = {};
                $scope.item.class = tipo;
            };

            function search(filter){
                $http({
                    url: 'api/vehiculo/search',
                    method: 'POST',
                    data: filter,
                    transformRequest: function (data, headersGetter) {
                        var formData = new FormData();
                        angular.forEach(data, function (value, key) {
                            formData.append(key, value);
                        });
                        var headers = headersGetter();
                        delete headers['Content-Type'];

                        return formData;
                    }
                }).then(function (response) {
                    $scope.data = response.data;
                    globalData = response.data;
                });
            }

            $scope.applyFilter = function (filter){
                search(filter);
            };

            /**
             * Cargar el listado de neumaticos de un vehiculo
             * @param vehiculoid
             */
            function loadNeumaticos(vehiculoid){
                $http.get('api/neumatico/by_vehiculo/'+vehiculoid).then(function (response) {
                    $scope.neumaticos = response.data;
                    $scope.vehiculoSeleccionado = vehiculoid;
                    $scope.activeNeumaticos = 0;
                    angular.forEach($scope.neumaticos, function(value, key) {
                        if (value.activo){
                            $scope.activeNeumaticos++;
                        }
                    });
                    $scope.canAddNeumaticos = ($scope.activeNeumaticos < $scope.vehiculoSelected.cantidadNeumaticos) ? true : false;
                    $('#neumaticoModal').modal('show');
                });

            }

            /**
             * Cargar las baterias de un vehiculo
             * @param vehiculoid
             */
            function loadBaterias(vehiculoid){
                $http.get('api/bateria/by_vehiculo/'+vehiculoid).then(function (response) {
                    //debugger;
                    $scope.baterias = response.data;
                    $scope.vehiculoSeleccionado = vehiculoid;

                    $scope.activeBateries = 0;
                    angular.forEach($scope.baterias, function(value, key) {
                       if (value.activo){
                           $scope.activeBateries++;
                       }
                    });
                    $scope.canAddBateries = ($scope.activeBateries < $scope.vehiculoSelected.cantidadBaterias) ? true : false;

                    $('#bateriaModal').modal('show');
                });
            }

            /**
             * Cargar los motores de un vehiculo
             * @param vehiculoid
             */
            function loadMotor(vehiculoid){
                $http.get('api/motor/by_vehiculo/'+vehiculoid).then(function (response) {
                    $scope.motores = response.data;
                    $scope.vehiculoSeleccionado = vehiculoid;
                    $scope.motorAnterior = ($scope.motores.length > 0) ? $scope.motores[0] : {};
                    //console.log($scope.motores);
                    $('#motorModal').modal('show');
                });
            }


            /**
             * Carcando los Indice de consumo de un vehiculo
             * @param vehiculoid
             */
            function loadIndiceConsumo(vehiculoid){

                $http.get('api/indiceconsumo/by_vehiculo/'+vehiculoid).then(function (response) {
                    //debugger
                    $scope.consumos = response.data;
                    $scope.vehiculoSeleccionado = vehiculoid;
                    $scope.indiceConsumoAnterior = ($scope.consumos.length > 0) ? $scope.consumos[0] : {};
                    $('#indiceConsumoModal').modal('show');
                });

            }

            /**
             * Cargando las matriculas de un vehiculo.
             * @param vehiculoid
             */
            function loadMatriculas(vehiculoid){
                $http.get('api/matricula/by_vehiculo/'+vehiculoid).then(function (response) {
                    $scope.matriculas = response.data;
                    $scope.vehiculoSeleccionado = vehiculoid;
                    $scope.matriculaAnterior = ($scope.matriculas.length > 0) ? $scope.matriculas[0] : {};
                    $('#matriculaModal').modal('show');
                });
            }

            /**
             * Cargando las carrocerias de un vehiculo
             * @param vehiculoid
             * @param vehiculoid
             */
            function loadCarroceria(vehiculoid){
                $http.get('api/carroceria/by_vehiculo/'+vehiculoid).then(function (response) {
                    $scope.carrocerias = response.data;
                    $scope.carroceriaAnterior = (response.data.length > 0) ? response.data[0] : {};
                    $scope.vehiculoSeleccionado = vehiculoid;
                    $('#carroceriaModal').modal('show');
                });
            }

            CrudService.list('tipocombustible', $scope).then(function (data) {
                $scope.tipocombustibles = data;
            });

            /**
             * Cargar listado de vehiculos
             */
            function loadData() {

                CrudService.list('vehiculo', $scope, $routeParams.id).then(function (data) {
                    $scope.data = data;
                    //console.log($scope.data);
                });


                CrudService.list('dependencia', $scope).then(function (data) {
                    $scope.dependencias = data;
                });

                $http.get('api/clasificador/marcas').then(function (response) {
                    //debugger
                    $scope.clasificadores = response.data;
                    $scope.marcas_v = $scope.clasificadores.vehiculo.marcas;
                    $scope.marcas_c = $scope.clasificadores.carroceria.marcas;
                    $scope.marcas_n = $scope.clasificadores.neumaticos.marcas;
                    $scope.marcas_m = $scope.clasificadores.motor.marcas;
                    $scope.marcas_b = $scope.clasificadores.bateria.marcas;
                });

                CrudService.list('marca', $scope).then(function (data) {
                    $scope.marcas = data;
                });


                CrudService.list('modelo', $scope).then(function (data) {
                    $scope.modelos = data;
                });
            }

            $scope.createItem = function(){
                var item = {
                    editing: true,
                    class : $scope.tipoVehiculo,
                    contratado : false,
                };

                $scope.item = item;
                $scope.year = $scope.year;
                $scope.settings.cmd = 'New';
                $scope.filledClass = '';
                $scope.formErrors = null;
                showForm();
            };

            $scope.editItem = function (item) {
                if (item) {
                    item.editing = true;
                    $scope.item = item;
                    $scope.settings.cmd = 'Edit';
                    $scope.formErrors = {};
                    $scope.filledClass = 'filled';
                    $scope.formErrors = null;
                    showForm();
                }
            };

            $scope.viewItem = function (item) {
                if (item) {
                    item.editing = false;
                    $scope.item = item;
                    $scope.settings.cmd = 'View';
                    $scope.filledClass = 'filled';
                    $scope.imgUrl = 'vehiculo/image/'+item.id+'?'+new Date().getTime();
                    showForm();

                }
            };

            function saveVehiculo(vehiculo){


                CrudService.save('vehiculo', vehiculo).then(function (result) {
                    if (result.success == true) {
                        var errorAlert = $alert({ title: 'Aviso',
                            content: '<br>Información guardada correctamente</br>',
                            placement: 'top-right', type: 'theme', show: true,
                            animation: 'mat-grow-top-right'
                        });

                        $timeout(function () { errorAlert.show();
                            $timeout(function () { errorAlert.hide(); }, 10000);
                        }, 1);
                        loadData();
                        hideForm();
                    } else {
                        var errorAlert = $alert({ title: 'Alerta',
                            content: '<br>Error al guardar la información. Si el problema continua por favor contacte al admin del sistema</br>',
                            placement: 'top-right', type: 'theme', show: true,
                            animation: 'mat-grow-top-right'
                        });

                        $timeout(function () { errorAlert.show();
                            $timeout(function () { errorAlert.hide(); }, 10000);
                        }, 1);
                        $scope.formErrors = ErrorService.parseErrors(result);
                    }
                }, function(response){
                    var errorAlert = $alert({ title: 'Alerta',
                        content: '<br>Error al guardar la información. Si el problema continua por favor contacte al admin del sistema</br>',
                        placement: 'top-right', type: 'theme', show: true,
                        animation: 'mat-grow-top-right'
                    });

                    $timeout(function () { errorAlert.show();
                        $timeout(function () { errorAlert.hide(); }, 10000);
                    }, 1);
                });
            }

            $scope.saveItem = function(item) {
                var vehiculo = item;
                vehiculo.indiceConsumo = item.indiceConsumoActual;

                if (!vehiculo.imagen){
                    vehiculo.imagen = 'uploads/nophoto.jpg';
                }
                if (item.dependencia !== null && typeof item.dependencia === 'object'){
                     vehiculo.dependencia = item.dependencia.id;
                }
                if (isObject(item.modelo)){
                    vehiculo.modelo = item.modelo.id;
                }

                if (isObject(vehiculo.tipoCombustible)){
                    vehiculo.tipoCombustible = item.tipoCombustible.id;
                }
                else{
                    vehiculo.tipoCombustible = item.tipoCombustible;
                }

                vehiculo.ficavFechaActualizacion = moment(item.ficavFechaActualizacion).format('YYYY-MM-DD');
                vehiculo.ficavFechaVencimiento = moment(item.ficavFechaVencimiento).format('YYYY-MM-DD');
                vehiculo.registroFechaVencimiento = moment(item.registroFechaVencimiento).format('YYYY-MM-DD');
                vehiculo.registroFechaActulizacion = moment(item.registroFechaActulizacion).format('YYYY-MM-DD');

                //vehiculo.tipoVehiculo = $scope.tipoVehiculo;

                if (vehiculo.class == 'carga') {

                    vehiculo.fechaActualizacionLicenciaOperaiva = moment(item.fechaActualizacionLicenciaOperaiva).format('YYYY-MM-DD');
                    vehiculo.fechaVencimientoLicenciaOperativa = moment(item.fechaVencimientoLicenciaOperativa).format('YYYY-MM-DD');

                }

                if (vehiculo.class == 'cisterna') {

                    vehiculo.fechaActualizacionLicenciaOperaiva = moment(item.fechaActualizacionLicenciaOperaiva).format('YYYY-MM-DD');
                    vehiculo.fechaVencimientoLicenciaOperativa = moment(item.fechaVencimientoLicenciaOperativa).format('YYYY-MM-DD');

                    vehiculo.aforoFechaActualizacion = moment(item.aforoFechaActualizacion).format('YYYY-MM-DD');
                    vehiculo.aforoFechaVencimiento = moment(item.aforoFechaVencimiento).format('YYYY-MM-DD');
                    vehiculo.flujometroFechaActualizacion = moment(item.flujometroFechaActualizacion).format('YYYY-MM-DD');
                    vehiculo.flujometroFechaVencimiento = moment(item.flujometroFechaVencimiento).format('YYYY-MM-DD');
                }

                vehiculo.class = vehiculo.class;

                if (item.file){
                    //upload the files.
                    Upload.upload({
                        url: 'api/vehiculo/upload_photo',
                        method: 'POST',
                        file: item.file,
                        data: {
                            'targetPath' : './uploads/vehiculos/'
                        }
                    }).then(function (response) {
                        var path = 'uploads/vehiculos/'+response.data.file_name;
                        vehiculo.imagen = path;
                        saveVehiculo(vehiculo);
                    }, function (result) {
                        console.log('Error status: ' + result.message);
                        $scope.formErrors = ErrorService.parseErrors(result);
                    });
                }
                else{
                    saveVehiculo(vehiculo);
                }

            };


            ///Gestion de consumo
            $scope.ManageIndiceConsumo = function(item){
                $scope.consumo = {};
                $scope.vehiculoSelected = item;
                loadIndiceConsumo(item.id);
            };

            $scope.createIndiceConsumo = function () {
                $scope.addIndiceConsumo = true;
            };

            $scope.saveIndiceConsumo = function (consumo) {

                consumo.vehiculoSeleccionado = $scope.vehiculoSeleccionado;

                if ($scope.consumos.length > 0) {
                    console.log('Obtener consumo actual y pasarlo para el historial');
                }

                consumo.fecha = moment(consumo.fecha).format('YYYY-MM-DD');
                CrudService.save('indiceconsumo', consumo).then(function (result) {
                    if (result.success == true) {
                        hideForm();
                        loadData();
                        $scope.consumo = {};
                        $scope.formErrors = {};
                        $scope.addIndiceConsumo = false;
                        loadIndiceConsumo($scope.vehiculoSeleccionado);
                    } else {
                        $scope.formErrors = ErrorService.parseErrors(result);
                    }
                });
            };

            ///Gestion de Carrocerias
            $scope.ManageCarroceria = function(item){
                $scope.carroceria = {};
                $scope.vehiculoSelected = item;
                loadCarroceria(item.id);
            };

            $scope.createCarroceria = function () {
                $scope.addCarroceria = true;

            };

            $scope.saveCarroceria = function (carroceria) {
                //debugger
                carroceria.vehiculoSeleccionado = $scope.vehiculoSeleccionado;
                var cansave = true;
                carroceria.anterior_id = -1;
                $scope.formErrors = {};
                if ($scope.carrocerias.length > 0) {
                    carroceria.anterior_id = $scope.carroceriaAnterior.id;
                }

                if (cansave){
                    carroceria.fechaInstalacion = moment(carroceria.fechaInstalacion).format('YYYY-MM-DD');
                    carroceria.fechaFabricacion = moment(carroceria.fechaFabricacion).format('YYYY-MM-DD');
                    carroceria.fechaRetirado = moment(carroceria.fechaRetirado).format('YYYY-MM-DD');

                    CrudService.save('carroceria', carroceria).then(function (result) {
                        if (result.success == true) {
                            $scope.carroceria = {};
                            $scope.formErrors = {};
                            $scope.addCarroceria = false;
                            hideForm();
                            loadData();
                            loadCarroceria($scope.vehiculoSeleccionado);
                        } else {
                            $scope.formErrors = ErrorService.parseErrors(result);
                        }
                    });
                }
            };


            ///Gestion de Matricula
            $scope.ManageMatricula = function(item){
                $scope.matricula = {};
                $scope.vehiculoSelected = item;
                loadMatriculas(item.id);
            };

            $scope.createMatricula = function () {
                $scope.addMatricula = true;
            };

            $scope.saveMatricula = function (matricula) {
                //debugger
                matricula.vehiculoSeleccionado = $scope.vehiculoSeleccionado;
                matricula.fecha = moment(matricula.fecha).format('YYYY-MM-DD');
                CrudService.save('matricula', matricula).then(function (result) {
                    //debugger;
                    if (result.success == true) {
                        hideForm();
                        loadData();
                        $scope.matricula = {};
                        $scope.formErrors = {};
                        $scope.addMatricula = false;
                        loadMatriculas($scope.vehiculoSeleccionado);
                    } else {
                            $timeout(function () {
                            errorAlert.show();
                            $timeout(function () {
                                errorAlert.hide();
                            }, 10000);
                        }, 1);
                    }
                });
            };



            //gestion de motores
            $scope.ManageMotores = function(item){
                $scope.motor = {};
                $scope.vehiculoSelected = item;
                loadMotor(item.id);
            };

            $scope.createMotor = function () {
                $scope.addMotor = true;
            };

            $scope.saveMotor = function (motor) {
                //debugger;
                motor.vehiculoSeleccionado = $scope.vehiculoSeleccionado;
                var cansave = true;
                var today = new Date();
                motor.anterior_id = -1;
                $scope.formErrors = {};

                if ($scope.motores.length > 0) {
                    motor.anterior_id = $scope.motorAnterior.id;
                }

                motor.fechaInstalado = moment(motor.fechaInstalado).format('YYYY-MM-DD');
                motor.fechaRetirado = moment(motor.fechaRetirado).format('YYYY-MM-DD');

                //console.log(motor);
                if (cansave) {
                    CrudService.save('motor', motor).then(function (result) {
                        if (result.success == true) {
                            $scope.motor = {};
                            $scope.formErrors = {};
                            $scope.addMotor = false;
                            hideForm();
                            loadData();
                            loadMotor($scope.vehiculoSeleccionado);
                        } else {
                            $scope.formErrors = ErrorService.parseErrors(result);
                        }

                    });
                }
            };


            // neumaticos

            $scope.ManageNeumaticos = function(item){
                $scope.neumatico = {};
                $scope.vehiculoSelected = item;
                loadNeumaticos(item.id);
            };

            $scope.createNeumatico = function () {
                $scope.addNeumatico = true;
            };

            $scope.changeStatusNeumatico = function(neumatico){
                //debugger;
                var newstatus = (neumatico.activo) ? 0 : 1;

                var info = {
                    'activo' : newstatus,
                    'id' : neumatico.id
                };

                $scope.updateNeumatico(info);

            };

            $scope.updateNeumatico = function(neumatico){
                CrudService.update('neumatico', neumatico).then(function (result) {
                    if (result.success == true) {
                        loadData();
                        $scope.neumatico = {};
                        loadNeumaticos($scope.vehiculoSeleccionado);
                    } else {
                        $scope.formErrors = ErrorService.parseErrors(result);
                    }
                });
            };

            $scope.saveNeumatico = function (neumatico) {

                neumatico.vehiculoSeleccionado = $scope.vehiculoSeleccionado;
                neumatico.fechaFabricacion = moment(neumatico.fechaFabricacion).format('YYYY-MM-DD');
                neumatico.fechaInstalacion = moment(neumatico.fechaInstalacion).format('YYYY-MM-DD');

                $scope.formErrors = {};

                if (new Date(neumatico.fechaInstalacion) < new Date(neumatico.fechaFabricacion)){
                    $scope.formErrors.fechaInstalacion = 'La fecha de intalación del numático debe ser mayor que la fecha de fabricación';
                } else if ($scope.neumaticos.length > 0 && new Date(neumatico.fechaFabricacion) < new Date($scope.neumaticos[0].fechaInstalado)) {
                    $scope.formErrors.fechaInstalacion = 'La fecha de intalación del numático debe ser mayor que la del neumático actual';
                } else {

                    CrudService.save('neumatico', neumatico).then(function (result) {
                        if (result.success == true) {
                            $scope.neumatico = {};
                            $scope.formErrors = {};
                            $scope.addNeumatico = false;
                            hideForm();
                            loadData();
                            loadNeumaticos(neumatico.vehiculoSeleccionado);
                        } else {
                            $scope.formErrors = ErrorService.parseErrors(result);
                        }

                    });
                }

            };


            //neumaticos end
            //
            // bateria

            $scope.ManageBateria = function(item){

                $scope.bateria = {};
                $scope.vehiculoSelected = item;
                loadBaterias(item.id);
            };

            $scope.changeStatusBateria = function(bateria){
                debugger;
                var newstatus = (bateria.activo) ? 0 : 1;

                var bateria = {
                    'activo' : newstatus,
                    'id' : bateria.id
                };

                $scope.updateBateria(bateria);

            };

            $scope.createBateria = function () {
                $scope.addBateria = true;
            };

            $scope.updateBateria = function(bateria){
                CrudService.update('bateria', bateria).then(function (result) {
                    if (result.success == true) {
                        loadData();
                        $scope.bateria = {};
                        loadBaterias($scope.vehiculoSeleccionado);
                    } else {
                        $scope.formErrors = ErrorService.parseErrors(result);
                    }
                });
            };

            $scope.saveBateria = function (bateria) {

                bateria.vehiculoSeleccionado = $scope.vehiculoSeleccionado;
                bateria.fechaFabricacion = moment(bateria.fechaFabricacion).format('YYYY-MM-DD');
                bateria.fechaInstalacion = moment(bateria.fechaInstalacion).format('YYYY-MM-DD');

                $scope.formErrors = {};
                if (new Date(bateria.fechaInstalacion) < new Date(bateria.fechaFabricacion)){
                    $scope.formErrors.fechaInstalacion = 'La fecha de intalación de la bateria debe ser mayor que la fecha de fabricación';
                } else {

                    CrudService.save('bateria', bateria).then(function (result) {
                        if (result.success == true) {
                            hideForm();
                            loadData();
                            $scope.bateria = {};
                            $scope.formErrors = {};
                            $scope.addBateria = false;
                            loadBaterias($scope.vehiculoSeleccionado);
                        } else {
                            $scope.formErrors = ErrorService.parseErrors(result);
                        }

                    });
                }

            };

            //bateria end

            // form

            var formTpl = $aside({
                scope: $scope,
                template: 'vehiculo/create',
                show: false,
                placement: 'left',
                backdrop: true,
                animation: 'am-slide-left'
            });

            showForm = function(){
                angular.element('.tooltip').remove();
                formTpl.show();
                $scope.formErrors = null;
            };

            hideForm = function(){
                formTpl.hide();
            };

            $scope.$on('$destroy', function() {
                hideForm();
            });

            // file handler

            $scope.fileReaderSupported = window.FileReader !== undefined && (window.FileAPI === undefined || FileAPI.html5 !== false);

            thumbHandler = function(file) {
                generateThumb(file);
            };

            generateThumb = function(file) {
                if (file !== undefined) {
                    if ($scope.fileReaderSupported && file.type.indexOf('image') > -1) {
                        $timeout(function() {
                            var fileReader = new FileReader();
                            fileReader.readAsDataURL(file);
                            fileReader.onload = function(e) {
                                $timeout(function() {
                                    file.dataUrl = e.target.result;
                                });
                            };
                        });
                    }
                }
            };

            $scope.$watch('item.file', function(file) {

                $scope.formUpload = false;
                if (file !== undefined && file !== null) {
                    for (var i = 0; i < file.length; i++) {
                        $scope.errorMsg = undefined;
                        (thumbHandler)(file[i]);
                    }
                }
            });
        }
    ]
)