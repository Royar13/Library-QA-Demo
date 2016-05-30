angular.module("library").controller("topBarCtrl", function ($scope, $http, $location, userService) {
    $scope.user = {};

    userService.getUser().then(function (user) {
        $scope.user = user;
    }, function () {
        $location.path("/");
    });
    $scope.disconnect = function () {
        userService.disconnect();
    };
});