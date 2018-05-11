app.controller('ModeloController',
    ['$http', '$scope', '$aside', 'ErrorService', 'CrudService', '$timeout', '$alert',
        function ($http, $scope, $aside, ErrorService, CrudService, $timeout, $alert) {
            $scope.settings = {
                singular: 'Modelo',
                plural: 'Modelo',
                cmd: 'Adicionar'
            };

            $http.get('api/clasificador/marcas').then(function (response) {

                $scope.clasificadores = response.data;
                //console.log($scope.clasificadores);

                $scope.marcasc = [];
                angular.forEach($scope.clasificadores, function(value, key) {
                    //debugger
                    $scope.marcasc[value.id] = value.marcas;
                });
                //debugger
                $scope.marcas_v = $scope.clasificadores.vehiculo.marcas;
                $scope.marcas_c = $scope.clasificadores.carroceria.marcas;
                $scope.marcas_n = $scope.clasificadores.neumaticos.marcas;
                $scope.marcas_m = $scope.clasificadores.motor.marcas;
                $scope.marcas_b = $scope.clasificadores.bateria.marcas;
                //var i = $scope.marcas+'_b';
                //console.log($scope.clasificadores.vehiculo.marcas);
            });
            $scope.marcasSelected = [];
            $scope.get_marca = function (clasificador){
                //debugger;
                console.log(clasificador);
                $scope.marcasSelected = $scope.marcasc[clasificador];
            };

            CrudService.list('marca', $scope).then(function (data) {
                $scope.marcas = data;
                //console.log($scope.marcas);
            });


            CrudService.list('modelo', $scope).then(function (data) {
                $scope.modelos = data;
            });

            loadData();

            function loadData() {
                CrudService.list('modelo', $scope, null).then(function (data) {
                    $scope.data = data;
                });
            }

            CrudService.list('marca', $scope, null).then(function (data) {
                //console.log(data);
                $scope.marcas = data;
            });

            $scope.createItem = function(){
                var item = {
                    editing: true,
                    clasificador: 0
                };

                $scope.item = item;
                $scope.settings.cmd = 'New';
                $scope.filledClass = '';
                $scope.formErrors = {};
                $scope.formErrors = null;
                showForm();
            };

            $scope.editItem = function (item) {
                if (item) {
                    //debugger;
                    $scope.marcasSelected = $scope.marcasc[item.clasificador.id];
                    //$scope.item.marca = item.marca.id;
                    item.editing = true;
                    $scope.item = item;
                    $scope.settings.cmd = 'Edit';
                    $scope.formErrors = {};
                    $scope.filledClass = 'filled';
                    $scope.formErrors = null;
                    showForm();
                }
            }

            $scope.viewItem = function (item) {
                if (item) {
                    item.editing = false;
                    $scope.item = item;
                    $scope.settings.cmd = 'View';
                    $scope.filledClass = 'filled';
                    showForm();

                }
            };

            $scope.saveItem = function(item) {

                if (isObject(item.marca)){
                    item.marca_id = item.marca.id;
                }
                else{
                    item.marca_id = item.marca;
                }

                CrudService.save('modelo', item).then(function (result) {
                    //console.log(result);
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

            //$scope.saveItem = function(item) {
            //    if ($scope.settings.cmd == 'New') {
            //        CrudService.save('modelo', item).then(function (result) {
            //            if (result == 'success') {
            //                loadData();
            //                hideForm();
            //            } else {
            //                $scope.formErrors = ErrorService.parseErrors(result);
            //            }
            //        })
            //    } else {
            //
            //        CrudService.update('modelo', item).then(function(result){
            //            if (result == 'success') {
            //                loadData();
            //                hideForm();
            //            } else {
            //                $scope.formErrors = ErrorService.parseErrors(result);
            //            }
            //        })
            //    }
            //}

            // form

            var formTpl = $aside({
                scope: $scope,
                template: 'modelo/create',
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

        }
    ]
);