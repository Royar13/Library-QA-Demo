angular.module("library").controller("loginCtrl", function ($scope, $http, userService, $location) {
    $scope.fields = {
        username: "",
        password: ""
    };
    $scope.errors = {};
    $scope.loading = false;
    $scope.login = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/login.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (response.data.success) {
                userService.updateUser(response.data.username, response.data.name);
                $location.path("/main");
            } else {
                $scope.errors = response.data.errors;
            }
        });
    };
});