/**
 * Created by Orlando on 28/10/2016.
 */
app.controller('UsuarioController',
    ['$http', '$scope', '$aside', 'CrudService', '$timeout', '$alert',
        function ($http, $scope, $aside, CrudService, $timeout, $alert) {
            $scope.settings = {
                singular: 'Usuario',
                plural: 'Usuarios',
                cmd: 'Adicionar'
            };

            loadData();
            function loadData(){
                //debugger
                CrudService.list('users', $scope).then(function (data) {
                    $scope.data = data;
                });
            };

            CrudService.list('rol', $scope).then(function (data) {
                $scope.roles = data;
            });

            CrudService.list('dependencia', $scope).then(function (data) {
                $scope.dependencias = data;
            });


            $scope.createItem = function(){
                var item = {
                    editing: true
                };

                $scope.item = item;
                $scope.settings.cmd = 'New';
                $scope.filledClass = '';
                $scope.formErrors = {};
                showForm();
            };



            $scope.editItem = function (item) {
                if (item){
                    item.editing = true;
                    $scope.item = item;
                    $scope.settings.cmd = 'Edit';
                    $scope.formErrors = {};
                    $scope.filledClass = 'filled';
                    $scope.formErrors = null;
                    console.log($scope.settings.cmd);
                    showForm();
                }
            };

            $scope.viewItem = function (item) {
                if (item){
                    item.editing = false;
                    $scope.item = item;
                    $scope.settings.cmd = 'View';
                    $scope.filledClass = 'filled';
                    showForm();
                }
            };

            $scope.remove = function (item) {
                if (item){
                    CrudService.remove('users', item.id).then(function (result) {
                        if (result.success == true){
                            loadData();
                        }
                    });
                }
            }

            $scope.checkPass = function(item){
                //debugger;
                //if (item.password != item.cpassword){
                //    $scope.formErrors.cpassword = 'La clave tiene que ser igual a la anterior.';
                //    //cansave = false;
                //}
            }

            $scope.saveItem = function(item){
                //debugger
                item.dependencia_id = item.dependencia.id;
                var groups = [];
                for (var i = 0; i < item.roles.length; i++)
                    groups.push(item.roles[i].id);
                item.groups = groups;
                CrudService.save('users', item).then(function (result) {
                    if (result.success == true){
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
                    }
                    else{

                        var errorAlert = $alert({ title: 'Alerta',
                            content: '<br>Error al guardar la información. Si el problema continua por favor contacte al admin del sistema</br>',
                            placement: 'top-right', type: 'theme', show: true,
                            animation: 'mat-grow-top-right'
                        });

                        $timeout(function () { errorAlert.show();
                            $timeout(function () { errorAlert.hide(); }, 10000);
                        }, 1);
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

            var formTpl = $aside({
                scope: $scope,
                template: 'usuario/create',
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