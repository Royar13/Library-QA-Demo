angular.module("library").controller("displayBooksCtrl", function ($scope, $http, userService) {
    $scope.books = [];
    $scope.quantity = 50;
    $scope.showEditBtn = function () {
        return userService.hasPermission(5);
    };
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllBooks"}
    }).then(function (response) {
        $scope.books = response.data.books;
    });
});