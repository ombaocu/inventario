var app = angular.module('INVENTARIO', [
    'app.constants',
    'ngRoute',
    'ngCookies',
    'ngAnimate',
    'ngSanitize',
    'ngPlaceholders',
    'ngTable',
    'angular-loading-bar',
    'uiGmapgoogle-maps',
    'ui.select',
    'gridshore.c3js.chart',
    'monospaced.elastic',     // resizable textarea
    'mgcrea.ngStrap',
    'jcs-autoValidate',
    'ngFileUpload',
    'textAngular',
    'fsm',                    // sticky header
    'smoothScroll',
    'LocalStorageModule'
]).constant('AUTH_EVENTS', {
        loginSuccess: 'auth-login-success',
        loginFailed: 'auth-login-failed',
        logoutSuccess: 'auth-logout-success',
        sessionTimeout: 'auth-session-timeout',
        notAuthenticated: 'auth-not-authenticated',
        notAuthorized: 'auth-not-authorized'
    })
    .constant('USER_ROLES', {
        all: '*',
        admin: 'admin',
        joperations: 'JefeDeOperaciones',
        espusoracenergia: 'EspUsoRacionalEnergia',
        staff: 'members'
        //guest: 'guest'
    })
    .constant('inventarioAppConfig', {
        'backend': '<webapp>api/<action>/',
        'version': 0.1
    })
    .run(function($rootScope, $location, AUTH_EVENTS, $http, SessionService, $window, HelpersService, $cookieStore, UserService){
        $rootScope.security = {
            active : true
        };
        $window.getCookie = function(name) {
            match = document.cookie.match(new RegExp(name + '=([^;]+)'));
            if (match) return match[1];
        }

        //debugger

        //var identity2 = $cookieStore.get('identity');

        $rootScope.$on( "$routeChangeStart", function(event, next, current) {
            //var identity = $window.document.cookie;
            //if (identity == ''){
            //
            //}
            //else{
            //
            //}
            //var user_id = $window.getCookie('user_id');
            //if (user_id){
            //    var user = UserService.getCurrent(user_id);
            //    $rootScope.currentUser = user;
            //}
            //else{
            //    $window.document.href = HelpersService.getBaseUrl() + 'auth/logout';
            //}
            $rootScope.currentUser = SessionService.user();
            if (!$rootScope.currentUser) {
                $window.document.href = HelpersService.getBaseUrl() + 'auth/logout';
            }
        });
        //debugger

        //if (!$rootScope.currentUser){
        //    $window.location.href = HelpersService.getBaseUrl() + '/auth/login';
        //}
    });

var configFunction = function ($routeProvider) {
    //debugger
    $routeProvider.when('/', {
        templateUrl: 'home/dashboard'
    }).when('/:controller/:action', {
        templateUrl: function (attr) {
            return attr.controller + '/' + attr.action;
        }
    }).when('/:controller', {
        templateUrl: function (attr) {
            return attr.controller + '/index';
        }
    }).when('/:controller/:action/:id', {
        templateUrl: function(attr){
            return attr.controller+'/'+attr.action+'/'+attr.id;
        }
    }).otherwise({ redirectTo: '/' });

    //$httpProvider.interceptors.push('AuthHttpResponseInterceptor');
};

configFunction.$inject = ['$routeProvider'];
app.config(configFunction);


angular.module('app.constants', [])
    .constant('APP', { version: '1.0.0' });


