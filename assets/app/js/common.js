/**
 * Created by Orlando on 10/09/2016.
 */
function isObject(val) {
    if (val === null) { return false;}
    return ( (typeof val === 'function') || (typeof val === 'object') );
}

//var makeSymmDiffFunc = (function() {
//    var contains = function(pred, a, list) {
//        var idx = -1, len = list.length;
//        while (++idx < len) {if (pred(a, list[idx])) {return true;}}
//        return false;
//    };
//    var complement = function(pred, a, b) {
//        return a.filter(function(elem) {return !contains(pred, elem, b);});
//    };
//    return function(pred) {
//        return function(a, b) {
//            return complement(pred, a, b).concat(complement(pred, b, a));
//        };
//    };
//}());
//
//var tipoCombustibleDiff = makeSymmDiffFunc(function(x, y) {
//    return x.id === y.combustible.id;
//});

function diff(a, b){
    var onlyInA = a.filter(function(current){
        return b.filter(function(current_b){
                return current_b.combustible.id == current.id
            }).length == 0
    });

    var onlyInB = b.filter(function(current){
        return a.filter(function(current_a){
                return current_a.id == current.combustible.id
            }).length == 0
    });

    result = onlyInA.concat(onlyInB);
    return result;
}