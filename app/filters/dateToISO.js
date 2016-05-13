angular.module("library").filter('dateToISO', function () {
    return function (input) {
        return new Date(input).toISOString();
    };
});