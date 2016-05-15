angular.module("library").controller("displayBooksCtrl", function ($scope, $http) {
    $scope.books = [];
    $scope.quantity = 50;
    $http({
        method: "post",
        url: "./dist/server/readBooks.php"
    }).then(function (response) {
        $scope.books = response.data.books;
    });
});