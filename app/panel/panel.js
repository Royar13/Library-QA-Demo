angular.module("library").controller("panelCtrl", function ($scope, $window, $location, alertify) {
    alertify.logPosition("top right").okBtn("אישור").cancelBtn("ביטול");

    var bgRatio = 1.67;
    var bgWidth = $(window).height() * bgRatio;
    var leftMargin = ($(window).width() - bgWidth) / 2;
    $scope.panelStyle = {};
    $scope.panelStyle.right = Math.max(leftMargin + 20, 20);
    //$scope.panelStyle.width = bgWidth * 0.7;
    //$scope.panelStyle.height = $(window).height() * 0.8;

    $scope.includeTopBar = function () {
        return $location.path() != "/";
    };
    $scope.loading = false;
});