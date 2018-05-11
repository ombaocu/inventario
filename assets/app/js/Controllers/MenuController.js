/**
 * Created by Orlando on 05/11/2016.
 */


app.controller('MenuController',
    ['$http', '$scope', '$aside', 'CrudService', 'SessionService', '$rootScope', 'UserService',
        function ($http, $scope, $aside, CrudService, SessionService, $rootScope, UserService) {
            $scope.settings = {
                singular: 'Menu',
                plural: 'Menus',
                cmd: 'Adicionar'
            };
            $scope.currentUser = SessionService.user();

            $http.get('api/users/'+$scope.currentUser.id).then(function (response) {
                $scope.permisos = response.data.permisos;
                $scope.currentUser = response.data;
            });

            $scope.planificacion = {
                bc3: true,
                bc4: true,
                cda001 : true,
            }
        }
    ]
);