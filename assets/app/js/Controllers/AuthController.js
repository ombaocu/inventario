/**
 * Created by Orlando on 19/11/2016.
 */
app.controller('AuthController',
    ['$http', '$scope', '$animate', 'localStorageService', '$alert', '$window', '$timeout', 'HelpersService', 'SessionService', 'USER_ROLES',
            'AuthService', '$rootScope', '$cookieStore', 'AUTH_EVENTS', '$location',
        function ($http, $scope, $animate, localStorageService, $alert, $window, $timeout, HelpersService, SessionService, USER_ROLES,
                  AuthService, $rootScope, $cookieStore, AUTH_EVENTS, $location) {
                var baseUrl = HelpersService.getBaseUrl();
                //debugger


                $scope.login = function (item) {
                        AuthService.login(item.identity, item.password).then(
                            function (response) {
                                    if (response.success) {

                                            $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                                            $rootScope.setCurrentUser = response.data;

                                             //Logged in, redirect to home
                                            if (typeof $cookieStore.get('returnUrl') != 'undefined' && $cookieStore.get('returnUrl') != '') {
                                                    var url = $cookieStore.get('returnUrl');
                                                    $cookieStore.remove('returnUrl');
                                                    // Hard redirect to prevent that angular add query string params
                                                    $window.location.href = url;
                                            } else {
                                                    /*
                                                     * Redirect to My Account page
                                                     */



                                                    var errorAlert = $alert({ title: 'Aviso',
                                                            content: '<br>Usted ha iniciado sesión correctamente</br>',
                                                            placement: 'top-right', type: 'theme', show: true,
                                                            animation: 'mat-grow-top-right'
                                                    });

                                                    $timeout(function () { errorAlert.show();
                                                            $timeout(function () { errorAlert.hide(); }, 10000);
                                                    }, 1);

                                                    //if (AuthService.isAuthorized(['Administrator'])) {
                                                    //        $location.path('/admin/dashboard');
                                                    //        //$location.path('/admin/agent/149/merchant/151/edit');
                                                    //} else if (AuthService.isAuthorized(['Agents'])) {
                                                    //        $location.path('/agent/dashboard');
                                                    //} else if (AuthService.isAuthorized(['Merchants'])) {
                                                    //        $location.path('/merchant/dashboard');
                                                    //} else if (AuthService.isAuthorized(['Staff'])) {
                                                    //        $location.path('/staff/dashboard');
                                                    //} else {
                                                    //        $location.path('/login');
                                                    //}
                                                    $window.location.href = baseUrl;
                                            }
                                    }
                                    else {
                                            //$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
                                            //
                                            $scope.msg = {};
                                            //
                                            $scope.msg.type = 'danger';
                                            if (response.error) {
                                                    $scope.msg.text = response.error;
                                            }
                                            else {
                                                    $scope.msg.text = 'Please check your Email and Password';
                                            }
                                            var errorAlert = $alert({ title: 'Alerta',
                                                    content: '<br>'+ $scope.msg.text+'</br>',
                                                    placement: 'top-right', type: 'theme', show: true,
                                                    animation: 'mat-grow-top-right'
                                            });

                                            $timeout(function () { errorAlert.show();
                                                    $timeout(function () { errorAlert.hide(); }, 10000);
                                            }, 1);
                                    }
                            });

                };

        }]);