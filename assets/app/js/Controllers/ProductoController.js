/**
 * Created by Orlando on 28/10/2016.
 */


app.controller('ProductoController',
    ['$http', '$scope', '$aside', 'Upload', '$timeout','$alert','$animate', 'ErrorService', 'CrudService', '$routeParams',
        function ($http, $scope, $aside, Upload, $timeout, $alert, $animate, ErrorService, CrudService, $routeParams) {
            $scope.settings = {
                singular: 'Producto',
                plural: 'Productos',
                cmd: 'Adicionar'
            };

            function loadData(){

                CrudService.list('producto', $scope, $routeParams.id).then(function (data) {
                    $scope.data = data;
                });

                CrudService.list('marca', $scope).then(function (data) {
                    $scope.marcas = data;
                });

                CrudService.list('modelo', $scope).then(function (data) {
                    $scope.modelos = data;
                });

                CrudService.list('clasificador', $scope).then(function (data) {
                    $scope.clasificadores = data;
                });
            }
            loadData();

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
                    CrudService.remove('producto', item.id).then(function (result) {
                        if (result.success == true){
                            loadData();
                        }
                    });
                }
            };

            function saveProducto(producto){

                CrudService.save('producto', producto).then(function (result) {
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

            $scope.saveItem = function(item){
                var producto = item;

                if (!producto.imagen){
                    producto.imagen = 'uploads/nophoto.jpg';
                }
                if (isObject(item.clasificador)){
                    producto.clasificador = item.clasificador.id;
                }
                if (isObject(item.modelo)){
                    producto.modelo = item.modelo.id;
                }

                if (item.file){
                    //upload the files.
                    Upload.upload({
                        url: 'api/producto/upload_photo',
                        method: 'POST',
                        file: item.file,
                        data: {
                            'targetPath' : './uploads/productos/'
                        }
                    }).then(function (response) {
                        var path = 'uploads/productos/'+response.data.file_name;
                        producto.imagen = path;
                        saveProducto(producto);
                    }, function (result) {
                        console.log('Error status: ' + result.message);
                        $scope.formErrors = ErrorService.parseErrors(result);
                    });
                }
                else{
                    saveProducto(producto);
                }
            }

            var formTpl = $aside({
                scope: $scope,
                template: 'producto/create',
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
);
