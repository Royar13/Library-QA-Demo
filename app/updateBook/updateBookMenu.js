angular.module("library").controller("updateBookMenuCtrl", function ($scope, $http, $location) {
    $scope.fields = {
        action: "bookExists"
    };

    $scope.searchBook = function () {
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
                $location.path("/updateBook").search({id: $scope.fields.id});
            }
        });
    };
});
