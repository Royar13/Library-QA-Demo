angular.module("library").controller("updateReaderMenuCtrl", function ($scope, $http, $location) {
    $scope.fields = {
        action: "readerExists"
    };

    $scope.searchReader = function () {
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
                $location.path("/updateReader").search({id: $scope.fields.id});
            }
        });
    };
});
