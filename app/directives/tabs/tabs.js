angular.module("library").directive("tabs", function () {
    return {
        restrict: "C",
        scope: true,
        controller: function ($scope) {
            $scope.selectedIndex = 0;                
        }
    };
});