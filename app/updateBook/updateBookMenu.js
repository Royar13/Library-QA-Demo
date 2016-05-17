angular.module("library").controller("updateBookMenuCtrl", function ($scope, $http, $location) {
    $scope.fields = {};

    $scope.searchBook = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/bookExists.php",
            data: $scope.fields
        }).then(function (response) {
            if (!response.data.success && $scope.fields.id != "" && $scope.fields.id != null) {
                $scope.generateSarcasm();
                return;
            }

            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
            } else {
                $location.path("/updateBook").search({id: $scope.fields.id});
            }
        });
    };
});
