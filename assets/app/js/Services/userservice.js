'use strict';

/**
 * @ngdoc service
 * @name dealpagesApp.UserService
 * @description
 * # UserService
 * Service in the dealpagesApp.
 */
app.factory('UserService', function UserService($http, inventarioAppConfig, HelpersService) {

                var userService = {};

                userService.getCurrent = function (user_id) {
                        return $http.get('api/users/'+user_id).then(function (response) {
                                    return response.data;
                            });
                };

                userService.changePassword = function ($previous_password, $new_password, userId) {

                        var data = {'previous_password': $previous_password, 'new_password': $new_password};

                        var apiEndpoint = inventarioAppConfig.backend;
                        apiEndpoint = apiEndpoint.replace('<webapp>', HelpersService.getMainUrl());
                        apiEndpoint = apiEndpoint.replace('<action>', 'users/current/password');

                        if (userId){
                            apiEndpoint += '?user_id=' + userId;
                        }

                        return $http.post(apiEndpoint, data).then(
                                    function (response) {
                                            var toReturn = {};

                                            if (response.status === 204) { // Ok
                                                    toReturn.success = true;
                                            }
                                            else {
                                                    toReturn.success = false;
                                                    toReturn.error = false;
                                            }
                                            return toReturn;
                                    },
                                    function (response) { // .error

                                            var toReturn = {};

                                            toReturn.success = false;

                                            if (response.status === 400) {
                                                    toReturn.error = 'Previous Password is invalid.';
                                            }
                                            else {
                                                    toReturn.error = false;
                                            }

                                            return toReturn;
                                    }

                            );
                };

                return userService;
        });
