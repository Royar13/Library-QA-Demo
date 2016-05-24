angular.module("library").controller("borrowBooksMenuCtrl", function ($scope, $http, $location) {
    $scope.fields = {};

    $scope.searchReader = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/readerExists.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
            } else {
                $location.path("/borrowBooks").search({id: $scope.fields.id});
            }
        });
    };
});
