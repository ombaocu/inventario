app.factory('CrudService', ['$http', function($http) {

    function list(controller, $scope, id){
        if (id != null) {
            return $http.get('api/'+controller + '/'+ id).then(function (response) {
                return response.data;
            });
        } else {
            return $http.get('api/'+controller + '/').then(function (response) {
                return response.data;
            });
        }
    }


    function remove(controller, id){
        return $http({
            url: 'api/'+controller+'/delete/'+id,
            method: 'DELETE'
        }).then(function(response){
            return response.data;
        });
    }

    function save(controller, data){
        return $http({
            url: 'api/'+controller+'/save',
            method: 'POST',
            data: data,
            headers: { 'Content-Type': 'multipart/form-data' },
            transformRequest: function (data, headersGetter) {
                var formData = new FormData();
                angular.forEach(data, function (value, key) {
                    formData.append(key, value);
                });
                var headers = headersGetter();
                delete headers['Content-Type'];

                return formData;
            }
        }).then(function(response){
            return response.data;
        },function(response){
            return {
                'success' : false,
                'status_code' :  response.status,
                'message' : response.data,
            }
        })
    }

    function update(controller, data){
        return $http({
            url: 'api/'+controller+'/update',
            method: 'POST',
            data: data,
            headers: { 'Content-Type': 'multipart/form-data' },
            transformRequest: function (data, headersGetter) {
                var formData = new FormData();
                angular.forEach(data, function (value, key) {
                    formData.append(key, value);
                });
                var headers = headersGetter();
                delete headers['Content-Type'];

                return formData;
            }
        }).then(function(response){
            return response.data;
        },function(response){
            //debugger
            return {
                'success' : false,
                'status_code' :  response.status,
                'message' : response.data,
            }
        })
    }

    return {
        list: list,
        save: save,
        update: update,
        remove: remove
    }

}]);