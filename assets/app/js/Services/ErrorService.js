app.factory('ErrorService', function() {
    function parseErrors(errors){
        debugger;
        var errorMessages = {};

        for (var i=0;i<errors.length; i++){

            var defaultMessage = errors[i].defaultMessage
            var arguments = errors[i].arguments;
            for (var j=0;j<arguments.length; j++){
                defaultMessage = defaultMessage.replace('{'+j+'}', arguments[j]);
            }
            errorMessages[errors[i].field] = defaultMessage;
            //errorMessages.push({field: errors[i].field, message: defaultMessage});
        }

        return errorMessages;
    }

    return {
        parseErrors: parseErrors
    }
});