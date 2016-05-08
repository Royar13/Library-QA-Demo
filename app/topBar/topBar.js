angular.module("library").controller("topBarCtrl", function ($scope, $http, $location, userService) {
    var userCallback = function () {
        $scope.username = userService.username;
        $scope.name = userService.name;
    };
    userService.getUser().then();
    $scope.disconnect = function () {
        $http({
            method: "post",
            url: "./server/disconnect.php"
        }).then(function (response) {
            $location.path("/");
        });
    };
});