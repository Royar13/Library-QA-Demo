angular.module("library").controller("displayReadersCtrl", function ($scope, $http) {
    $scope.readers = [];
    $scope.quantity = 50;
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllReaders"}
    }).then(function (response) {
        $scope.readers = response.data.readers;
    });
});