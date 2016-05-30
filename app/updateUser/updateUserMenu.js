angular.module("library").controller("updateUserMenuCtrl", function ($scope, $http, $location) {
    $scope.fields = {
        action: "userExists"
    };

    $scope.searchUser = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            if (!response.data.success) {
                $scope.loading = false;
                $scope.errors = response.data.errors;
            } else {
                $location.path("/updateUser").search({id: response.data.id});
            }
        });
    };
});
