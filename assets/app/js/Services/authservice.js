'use strict';

/**
 * @ngdoc service
 * @name inventario.AuthService
 * @description
 * # AuthService
 */
app.service('AuthService', function AuthService($http, inventarioAppConfig, HelpersService, SessionService, USER_ROLES) {

    var baseUrl = HelpersService.getBaseUrl();
    var authService = {};

    authService.isAuthenticated = function () {
        // Return according to SessionService status
        return !!SessionService.user();
    };

    authService.isAuthorized = function (authorizedRoles) {
        if (!angular.isArray(authorizedRoles)) {
            authorizedRoles = [authorizedRoles];
        }
        return (authorizedRoles.indexOf(USER_ROLES.all) !== -1
            || (authService.isAuthenticated()
            && authorizedRoles.indexOf(SessionService.user().role) !== -1)
        );
    };

    authService.logout = function () {
        var apiEndpoint = baseUrl+'auth/logoutAction#/';

        return $http.post(apiEndpoint).then(

            function (response) {
                //debugger
                SessionService.destroy();

                return true;
            },
            function (response) { // .error
                //debugger
                return false;
            }
        );
    };

    authService.login = function (email, password, remember) {

        // logout first, just in case
        //authService.logout();

        /*
         * Proceed with login
         */

        var loginData = {'username': email, 'password': password, 'remember': remember};

        var apiEndpoint = baseUrl + 'auth/loginAction#/';
        //debugger
        return $http({
            url: apiEndpoint,
            method: 'POST',
            data: loginData,
            transformRequest: function (data, headersGetter) {
                var formData = new FormData();
                angular.forEach(data, function (value, key) {
                    formData.append(key, value);
                });
                var headers = headersGetter();
                delete headers['Content-Type'];

                return formData;
            }
        }).then(
            function (response) {
                var toReturn = {};
                //debugger
                if (response.data.success == true) {
                    var user = response.data.user;

                    SessionService.create(user);

                    toReturn.success = true;
                    toReturn.data = SessionService.user();
                }
                else {
                    toReturn.success = false;
                    toReturn.error = false;
                }
                return toReturn;
            },
            function (response) { // .error
                //debugger
                var toReturn = {};

                if (response.status === 401) { // Unauthorized
                    toReturn.success = false;
                    // In entity-body appears an specific error: wrong email, wrong password, disabled account
                    if (response.data.code === 401000) {
                        toReturn.error = 'The Email is invalid.';
                    }
                    else if (response.data.code === 401001) {
                        toReturn.error = 'The Password is invalid.';
                    }
                    else if (response.data.code === 401002) {
                        toReturn.error = 'The Account is disable.';
                    }
                    else {
                        toReturn.error = response.data.message;
                    }
                }
                else {
                    toReturn.success = false;
                    toReturn.error = false;
                }

                return toReturn;
            }
        );
    };

    authService.current = function () {

        var apiEndpoint = baseUrl + 'auth/current#/';

        return $http.get(apiEndpoint).then(
            function (response) {
                var toReturn = {};

                if (response.status === 200) {
                    /*
                     * Output: 200
                     * {
                         activation_code:""
                         active:"1"
                         company:"ADMIN"
                         created_on:"1268889823"
                         dependencia:Object
                         dependencia_id:"1"
                         dnombre:"San Jose"
                         email:"admin@admin.com"
                         first_name:"Admin"
                         forgotten_password_code:null
                         forgotten_password_time:null
                         groups:Array[1]
                         id:"1"
                         ip_address:"127.0.0.1"
                         last_login:"1479602023"
                         last_name:"istrator"
                         password:"$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36"
                         permisos:Array[0]
                         phone:"0"
                         remember_code:"nYGXuRckJevD6u..YvnAdu"
                         salt:""
                         username:"administrator"
                     * }
                     */



                    SessionService.create(response.data);

                    toReturn.success = true;
                    toReturn.data = SessionService.user();
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
                toReturn.error = false;

                return toReturn;
            }
        );
    };

    return authService;
});
