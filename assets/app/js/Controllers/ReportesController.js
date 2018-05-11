/**
 * Created by Orlando on 05/11/2016.
 */


app.controller('ReportesController',
    ['$http', '$scope', '$aside', 'CrudService',
        function ($http, $scope, $aside, CrudService) {
            $scope.settings = {
                singular: 'Reporte',
                plural: 'Reportes',
                cmd: 'Adicionar'
            };

            $scope.planificacion = {
                bc3: true,
                bc4: true,
                cda001 : true,
            };

            $scope.filter = {
                mes : 0,
                anno : 0,
                combustible: 1,
                action: 'conciliaciones_xls'

            };

            CrudService.list('tipocombustible', $scope).then(function (data){
                $scope.tipocombustibles = data;
            });

            $scope.applyFilter = function(filter){
                debugger;
                if (filter.anno == 0){
                    alert ('Seleccione un año');
                } else {
                    if (filter.anno != 0 ){
                        var q = 'anno='+ filter.anno;
                        if (filter.combustible != 0){
                            q += '&combustible=' + filter.combustible;
                        }
                        if (filter.mes != 0){
                            q += '&mes=' + filter.mes;
                        }
                        // report bc3 for a year
                        document.location = filter.action + '?'+q;
                    }
                }
            };

        }
    ]
);