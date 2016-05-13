angular.module("library").directive("generalErrors", function () {
    return {
        restrict: "A",
        scope: true,
        templateUrl: "app/directives/field/generalErrors.html"
    };
});