angular.module("library").controller("panelCtrl", function ($scope, $window, $location, alertify) {
    alertify.closeLogOnClick(true).logPosition("top right");

    var bgRatio = 1.67;
    var bgWidth = $(window).height() * bgRatio;
    var leftMargin = ($(window).width() - bgWidth) / 2;
    $scope.panelStyle = {};
    $scope.panelStyle.right = Math.max(leftMargin + 20, 20);
    //$scope.panelStyle.width = bgWidth * 0.7;
    //$scope.panelStyle.height = $(window).height() * 0.8;

    $scope.includeTopBar = function () {
        return $location.path() != "/";
    }
    $scope.loading = false;
    $scope.generateSarcasm = function () {
        var comments = new Array();
        comments.push("I used to be a tester like you, but then I took an arrow to the knee");
        comments.push("One does not simply test a library");
        comments.push("As part of a required test protocol, we will not monitor the next test chamber. You will be entirely on your own. Good luck.");
        comments.push("Hello and, again, welcome to the Aperture Science computer-aided enrichment center.");
        comments.push("To maintain a constant testing cycle, I simulate daylight at all hours and add adrenal vapor to your oxygen supply. So you may be confused about the passage of time. The point is, yesterday was your birthday. I thought you'd want to know.");
        comments.push("The Enrichment Center is required to remind you that you will be baked, and then there will be cake.");
        comments.push("You should have known better!");


        var randMsg = comments[randomNum(0, comments.length - 1)];
        alertify.delay(0).log(randMsg);
    };
    function randomNum(min, max) {
        var dif = max - min;
        var num = min + Math.round(Math.random() * dif);
        return num;
    }
});