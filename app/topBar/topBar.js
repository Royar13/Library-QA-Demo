angular.module("library").controller("topBarCtrl", function ($scope, $http, $location, userService) {
    $scope.user = {};
    var retrieveUser = function () {
        Object.keys(userService.user).forEach(function (key) {
            $scope.user[key] = userService.user[key];
        });
    };
    var request = userService.getUser();
    if (request === true) {
        retrieveUser();
    }
    else {
        request.then(function () {
            retrieveUser();
        });
    }
    $scope.disconnect = function () {
        $http({
            method: "post",
            url: "./server/disconnect.php"
        }).then(function () {
            $location.path("/");
        });
    };
});