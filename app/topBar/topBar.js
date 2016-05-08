angular.module("library").controller("topBarCtrl", function ($scope, $http, $location, userService) {
    var retrieveUser = function () {
        $scope.username = userService.username;
        $scope.name = userService.name;
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
        }).then(function (response) {
            $location.path("/");
        });
    };
});