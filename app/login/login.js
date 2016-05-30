angular.module("library").controller("loginCtrl", function ($scope, $http, $location, userService) {
    userService.getUser().then(function () {
        $location.path("/main");
    });

    $scope.fields = {
        action: "login"
    };
    $scope.login = function () {
        $scope.errors = {};
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            if (response.data.success) {
                userService.updateUser(response.data);
                $location.path("/main");
            } else {
                $scope.loading = false;
                $scope.errors = response.data.errors;
            }
        });
    };
});