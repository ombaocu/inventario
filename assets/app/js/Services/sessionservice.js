'use strict';

/**
 * @ngdoc service
 * @name dealpagesApp.Sessionservice
 * @description
 * # SessionService
 * Service in the dealpagesApp.
 */
app.service('SessionService', function SessionService($cookieStore, $window, $rootScope, UserService, $http) {
                
                /**
                 * General functions for Session
                 */

                //this.addValue = function (name, value) {
                //    console.log('add' + value);
                //    $cookieStore.put('optk' + name, value);
                //};
                //
                //this.removeValue = function (name) {
                //    $cookieStore.put('optk' + name, null);
                //};
                //
                //this.getValue = function (name) {
                //    var data = $cookieStore.get('optk' + name);
                //    console.log('get' + data);
                //    return data
                //};

                this.addValue = function (name,value) {
                    var valorjson = angular.toJson(value);
                    //$window.sessionStorage.setItem(name, value);
                    $window.sessionStorage.setItem(name, valorjson);
                };

                this.removeValue = function (name) {
                        //$cookieStore.put('inventario' + name, null);
                    //sessionStorage.inventario = null;
                    $window.sessionStorage.removeItem(name);
                };

                this.getValue = function (name) {
                    var valor =  $window.sessionStorage.getItem(name);
                    return angular.fromJson(valor);
                    //return valor;
                    //return $window.sessionStorage.getItem('inventario');

                };

                /*
                 * Sessions for Users
                 */

                this.userId = function () {
                        var user = this.user();
                        return user.id;
                };
                this.userRole = function () {
                        var user = this.user();
                        return user.role;
                };

                this.user = function () {
                        var inventarioUser = this.getValue('newUser');

                        if (inventarioUser) {
                                return inventarioUser;
                        }
                        else {
                                return false;
                        }
                };

                this.create = function (data) {
                    //debugger
                        var inventarioUser = {};

                        inventarioUser.id = data.id;
                        inventarioUser.role = data.role;
                        inventarioUser.role = data.username;
                        inventarioUser.firstName = data.first_name;
                        inventarioUser.lastName = data.last_name;
                        inventarioUser.name = data.first_name + ((data.last_name) ? ' ' + data.last_name : '');
                        inventarioUser.email = data.email;
                        inventarioUser.created = data.created;
                        inventarioUser.roles = data.groups;
                        inventarioUser.permisos = data.permisos;
                        inventarioUser.lastLogin = data.last_login;
                        inventarioUser.isActive = data.active;
                        inventarioUser.dependencia = data.dependencia;

                        this.addValue('newUser',  data);

                    $rootScope.userid = data.id;
                    $rootScope.userO = data;
                };

                this.destroy = function () {
                        this.removeValue('newUser');
                };

                return this;
        });
