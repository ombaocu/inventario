'use strict';

/**
 * @ngdoc service
 * @name dealpagesApp.HelpersService
 * @description
 * # HelpersService
 * Service in the dealpagesApp.
 */
app.service('HelpersService', ['$location','$route', function HelpersService($location, $route) {
                        // AngularJS will instantiate a singleton by calling "new" on this function

                        return {
                                isLinkActive: function (viewLocation) {
                                        var locations = viewLocation.split(',');
                                        var currentLocationPath = $route.current.originalPath;
                                        return locations.some(function (e, i, array) {
                                                return currentLocationPath.indexOf(e.trim()) >= 0;
                                        });
                                },
                                getRandomIntNumber: function (min, max) {

                                        min = typeof min !== 'undefined' ? min : 0;
                                        max = typeof max !== 'undefined' ? max : 1000;

                                        return Math.floor(Math.random() * (max - min) + min);
                                },
                                getMainUrl: function () {
                                        return $location.protocol() + '://' + $location.host() + ($location.port() !== 80 ? ':' + $location.port() : '') + '/';
                                },
                                getBaseUrl: function () {
                                        return $location.protocol() + '://' + $location.host() + ($location.port() !== 80 ? ':' + $location.port() : '') + '/inventario/';
                                },
                                htmlToPlainText: function (text) {
                                        text = String(text).replace(/<[^>]+>/gm, '');
                                        text = text.replace(/&nbsp;/gm,'');
                                        return text;
                                },
                                loadDefaultValues: function (targetObject, defaultValues) {
                                        var result = defaultValues;
                                        if (targetObject!= null) {
                                                for (var propertyName in targetObject) {
                                                        if (result.hasOwnProperty(propertyName)) {
                                                                result[propertyName]=targetObject[propertyName];
                                                        }
                                                }
                                        }
                                        return result;
                                }
                        };
                }]);
