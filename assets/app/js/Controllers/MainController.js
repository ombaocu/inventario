app.controller('MainController',
    ['$http', '$scope', '$animate', 'localStorageService', '$alert', '$timeout', 'HelpersService', 'SessionService', 'USER_ROLES',
        'AuthService','$route','$routeParams', '$location', 'AUTH_EVENTS', 'UserService', '$window', '$cookieStore',
        function ($http, $scope, $animate, localStorageService, $alert, $timeout, HelpersService, SessionService, USER_ROLES,
        AuthService, $route, $routeParams, $location, AUTH_EVENTS, UserService, $window, $cookieStore) {

            if (typeof (browser_old) == "undefined") {
                initRipplesWithArrive();

                $(document).arrive('.navbar-toggle', function () {
                    $(this).sideNav({ menuWidth: 260, closeOnClick: true });
                });
            }



            var logoutUser = function () {
                $scope.setCurrentUser(null);
                //SessionService.destroy();

                // Logout in backend
                AuthService.logout();
                var errorAlert = $alert({
                    title: 'Aviso',
                    content: '<br>Usted ha cerrado la sesión correctamente. Se esta redireccionando',
                    placement: 'top',
                    type: 'theme',
                    show: false,
                    //template: 'assets/tpl/partials/alert-introduction.html',
                    animation: 'mat-grow-top-center'
                });
                $timeout(function () {
                    errorAlert.show();
                    $timeout(function () {
                        errorAlert.hide();
                        $window.location.href = HelpersService.getBaseUrl() + '/';
                    }, 2000);
                }, 1);

            };
            $scope.logout = logoutUser;
            $scope.currentUser = SessionService.user();


            // Make accessible the Roles settings
            $scope.userRoles = USER_ROLES;

            // Assign authosization function from service
            $scope.isAuthorized = AuthService.isAuthorized;

            $scope.$route = $route;
            $scope.$location = $location;
            $scope.$routeParams = $routeParams;

            /*
             * Used to set for current users from other Controller
             */
            $scope.setCurrentUser = function (user) {
                $scope.currentUser = user;
            };

            // Define On events
            $scope.$on(AUTH_EVENTS.notAuthorized, function (event, data) {
                //403: Don't do anything, because is just not autorized, not need logout
                //logoutUser();
            });
            $scope.$on(AUTH_EVENTS.notAuthenticated, function (event, data) {
                logoutUser();
            });
            $scope.$on(AUTH_EVENTS.sessionTimeout, function (event, data) {
                logoutUser();
            });

            /*
             * Determine if menu link is active
             */
            $scope.isLinkActive = function ($viewLocation) {
                return HelpersService.isLinkActive($viewLocation, $location);
            };

           $scope.theme_colors = [
             'pink', 'red', 'purple', 'indigo', 'blue',
             'light-blue', 'cyan', 'teal', 'green', 'light-green',
             'lime', 'yellow', 'amber', 'orange', 'deep-orange'
             ];

            // Add todoService to scope
            //  service = new todoService($scope);
            // $scope.todosCount = service.count();
            /*$scope.$on('todos:count', function (event, count) {
             $scope.todosCount = count;
             element = angular.element('#todosCount');

             if (!element.hasClass('animated')) {
             $animate.addClass(element, 'animated bounce', function () {
             $animate.removeClass(element, 'animated bounce');
             });
             }
             });*/

            $scope.fillinContent = function () {
                $scope.htmlContent = 'content content';
            };

            localStorageService.set('theme', {
                color: 'theme-green',
                template: 'theme-template-dark'
            })

            //if (!localStorageService.get('theme')) {
            //    theme = {
            //        color: 'theme-pink',
            //        template: 'theme-template-dark'
            //    };
            //    localStorageService.set('theme', theme);
            //}
            localStorageService.bind($scope, 'theme');

            $scope.ngclass = '[theme.template, theme.color]'
            //$scope.theme.color = 'theme-green';
            //$scope.theme.template = 'theme-template-light';

            $scope.ChangePassword = function (OldPassword, Password, RPassword) {
                debugger
                if (Password != RPassword) {
                    $scope.error = "Las claves tienen que ser iguales";
                } else {

                    $http({
                        method: 'POST',
                        url: 'api/users/changepassword',
                        data: {oldPassowrd: OldPassword, password: Password, identity: $scope.currentUser.email},
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
                        if (response.success == true) {
                            var errorAlert = $alert({ title: 'Aviso',
                                content: '<br>Clave cambiada con exito!!!</br>',
                                placement: 'top-right', type: 'theme', show: true,
                                animation: 'mat-grow-top-right'
                            });

                            $timeout(function () { errorAlert.show();
                                $timeout(function () { errorAlert.hide(); }, 10000);
                            }, 1);


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
                        //debugger
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

            }

        }]);