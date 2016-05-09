angular.module("library").controller("topBarCtrl", function ($scope, $http, $location, userService) {
    $scope.user = {};
    function retrieveUser() {
        Object.keys(userService.user).forEach(function (key) {
            $scope.user[key] = userService.user[key];
        });
    }
    ;
    var request = userService.getUser();
    if (request === true) {
        retrieveUser();
    } else {
        request.then(function () {
            if (userService.user != null)
                retrieveUser();
            else
                $location.path("/");
        });
    }
    $scope.disconnect = function () {
        $http({
            method: "post",
            url: "./server/disconnect.php"
        }).then(function () {
            userService.user = null;
            $location.path("/");
        });
    };
});