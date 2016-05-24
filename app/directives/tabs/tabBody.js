angular.module("library").directive("tabBody", function () {
    return {
        restrict: "A",
        scope: true,
        transclude: true,
        replace: true,
        templateUrl: "app/directives/tabs/tabBody.html",
        controller: function ($scope, $element) {
            $scope.$watch("selectedIndex", function () {
                var index = $element.parent().find(".tab-body").index($element);
                $scope.show = (index == $scope.selectedIndex);
            });
        }
    };
});