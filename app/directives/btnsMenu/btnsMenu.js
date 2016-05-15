angular.module("library").directive("btnsMenu", function () {
    return {
        restrict: "A",
        scope: {},
        templateUrl: "app/directives/btnsMenu/btnsMenu.html",
        controller: function ($scope, $location, $window) {
            $scope.back = function () {
                $window.history.back();
            };
            $scope.home = function () {
                $location.path("/main");
            };
        }
    };
});