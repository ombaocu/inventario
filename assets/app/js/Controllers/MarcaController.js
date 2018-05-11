app.controller('MarcaController',
    ['$http', '$scope', '$aside', 'ErrorService', 'CrudService', '$timeout', '$alert',
        function ($http, $scope, $aside, ErrorService, CrudService, $timeout, $alert) {
            $scope.settings = {
                singular: 'Marca',
                plural: 'Marca',
                cmd: 'Adicionar'
            };

            loadData();

            function loadData() {
                CrudService.list('marca', $scope, null).then(function (data) {
                    $scope.data = data;
                })
            }

            $scope.createItem = function(){
                var item = {
                    editing: true
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
                if (isObject(item.clasificador)){
                    item.clasificadorid = item.clasificador.id;
                }
                else{
                    item.clasificadorid = item.clasificador;
                }
                CrudService.save('marca', item).then(function (result) {
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

            $scope.remove = function(item) {
                console.log(item);

                var result = confirm('¿Está seguro que desea eliminar?');
                //if (result){
                //    $http.get('autorizoCarga/deleteCarga?id='+item.id).then(function(){
                //        loadData();
                //    });
                //}
                //if (item) {
                //    item.editing = false;
                //    $scope.item = item;
                //    $scope.settings.cmd = 'View';
                //    $scope.filledClass = 'filled';
                //    showForm();
                //
                //}
            }

            // form

            var formTpl = $aside({
                scope: $scope,
                template: 'marca/create',
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