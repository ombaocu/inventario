/**
 * Created by Orlando on 28/10/2016.
 */
app.controller('RolController',
    ['$http', '$scope', '$aside', 'CrudService', 'ErrorService', '$timeout', '$alert',
        function ($http, $scope, $aside, CrudService, ErrorService, $timeout, $alert) {
            $scope.settings = {
                singular: 'Rol',
                plural: 'Roles',
                cmd: 'Adicionar'
            };

            $scope.filter = {
                entidad : '',
                rol : ''
            };
            loadData();
            function loadData(){
                CrudService.list('rol', $scope).then(function (data) {
                    $scope.data = data;
                });
            };

            CrudService.list('entidad', $scope).then(function (data) {
                $scope.entidades = data;
            });

            CrudService.list('accion', $scope).then(function (data) {
                $scope.acciones = data;
            });

            function slug(permiso){
                return $scope.rolSelected.slug +'_' + permiso.accion.slug+'_' + permiso.entidad.slug;
            };

            function parent_slug(permiso){
                return permiso.accion.slug+'_' + permiso.entidad.slug;
            };

            $scope.createItem = function(){
                var item = {
                    editing: true
                };
                $scope.item = item;
                $scope.settings.cmd = 'New';
                $scope.filledClass = '';
                $scope.formErrors = null;
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
                    CrudService.remove('rol', item.id).then(function (result) {
                        if (result.success == true){
                            loadData();
                        }
                    });
                }
            }

            $scope.saveItem = function(item){
                //item.tipo_combustible_id = item.tipo_combustible;
                CrudService.save('rol', item).then(function (result) {
                    if (result.success == true){
                        loadData();
                        hideForm();
                    }
                });
            };

            function loadPermisos(rolid){
                //debugger
                $http.get('api/rol/'+rolid+'/permisos').then(function (response) {
                    $scope.lpermisos = response.data;
                    $scope.rolSeleccionado = rolid;
                    $('#permisoModal').modal('show');
                });
            }

            //gestion de permisoes
            $scope.ManagePermisos = function(item){
                $scope.permiso = {};
                $scope.rolSelected = item;
                loadPermisos(item.id);
            };

            $scope.createPermiso = function () {
                $scope.addPermiso = true;
            };

            $scope.deletePermiso = function(permiso){
                CrudService.remove('permisos', permiso.id).then(function (result) {
                    if (result.success == true) {
                        loadData();
                        $scope.permiso = {};
                        $scope.formErrors = {};
                        $scope.addPermiso = false;
                        loadPermisos($scope.rolSelected.id);
                    } else {
                        $scope.formErrors = ErrorService.parseErrors(result);
                    }
                });
            };

            $scope.savePermiso = function (permiso) {
                //debugger
                permiso.entidad_id = permiso.entidad.id;
                permiso.accion_id = permiso.accion.id;
                permiso.groups_id = $scope.rolSelected.id;
                permiso.slug = slug(permiso);
                permiso.parent_slug = parent_slug(permiso);

                CrudService.save('permisos', permiso).then(function (result) {
                    if (result.success == true) {
                        hideForm();
                        var errorAlert = $alert({ title: 'Aviso',
                            content: '<br>Información guardada correctamente</br>',
                            placement: 'top-right', type: 'theme', show: true,
                            animation: 'mat-grow-top-right'
                        });

                        $timeout(function () { errorAlert.show();
                            $timeout(function () { errorAlert.hide(); }, 10000);
                        }, 1);

                        loadData();
                        $scope.permiso = {};
                        $scope.formErrors = {};
                        $scope.addPermiso = false;
                        loadPermisos($scope.rolSelected.id);

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
                    }, function(response) {
                        var errorAlert = $alert({
                            title: 'Alerta',
                            content: '<br>Error al guardar lso datos, por favor verifique sus datos e intentalo nuevamente. Si el problema continua por favor contacte al admin del sistema</br>',
                            placement: 'top-right', type: 'theme', show: true,
                            animation: 'mat-grow-top-right'
                        });

                        $timeout(function () {
                            errorAlert.show();
                            $timeout(function () {
                                errorAlert.hide();
                            }, 5000);
                        }, 1);
                    });
            };


            var formTpl = $aside({
                scope: $scope,
                template: 'rol/create',
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