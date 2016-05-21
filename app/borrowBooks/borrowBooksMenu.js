angular.module("library").controller("borrowBooksMenuCtrl", function ($scope, $http, $location) {
    $scope.fields = {
        action: "readerExists"
    };

    $scope.searchReader = function () {
        $scope.loading = true;
        $scope.errors = {};
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            if (!response.data.success) {
                $scope.loading = false;
                $scope.errors = response.data.errors;
            } else {
                $location.path("/borrowBooks").search({id: $scope.fields.id});
            }
        });
    };
});
