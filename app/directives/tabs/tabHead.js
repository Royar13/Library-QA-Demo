angular.module("library").directive("tabHead", function () {
    return {
        restrict: "A",
        scope: true,
        transclude: true,
        replace: true,
        templateUrl: "app/directives/tabs/tabHead.html",
        controller: function($scope, $element) {
            $scope.$watch("selectedIndex", function () {
                var index = $element.parent().find(".tab-head").index($element);
                $scope.class = (index == $scope.selectedIndex)?"selected":"";
            });        },
        link: function (scope, elem, attrs) {
            elem.bind("click", function () {
                scope.$apply(function () {
                    scope.$parent.selectedIndex = elem.parent().find(".tab-head").index(elem);
                });
            });
        }
    };
});