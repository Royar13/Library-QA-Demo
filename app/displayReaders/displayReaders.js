angular.module("library").controller("displayReadersCtrl", function ($scope, $http) {
    $scope.readers = [];
    $http({
        method: "post",
        url: "./server/readReaders.php"
    }).then(function (response) {
        $scope.readers = response.data.readers;
    });
});