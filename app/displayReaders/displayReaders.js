angular.module("library").controller("displayReadersCtrl", function ($scope, $http) {
    $scope.readers = [];
    $scope.quantity = 50;
    $http({
        method: "post",
        url: "./dist/server/readReaders.php"
    }).then(function (response) {
        $scope.readers = response.data.readers;
    });
});