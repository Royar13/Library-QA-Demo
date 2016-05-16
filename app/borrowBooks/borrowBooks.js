angular.module("library").controller("borrowBooksCtrl", function ($scope, $http, $routeParams, alertify) {
    $scope.readerId = $routeParams.id;
    $scope.isReturn = [];
    $scope.borrows = [{}];
    $scope.m={};
    $scope.$watch("m", function() {
    });
    $scope.fields = {
        readerId: "",
        borrowBooksId: [],
        returnBooksId: []
    };
    $scope.errors = {};
    $scope.select = {};
    $http({
        method: "post",
        url: "./server/readReader.php",
        data: {id: $scope.readerId}
    }).then(function (response) {
        $scope.readerName = response.data.name;
    });
    $http({
        method: "post",
        url: "./server/readBorrowedBooks.php",
        data: {id: $scope.readerId}
    }).then(function (response) {
        $scope.readerName = response.data.name;
    });
    $http({
        method: "post",
        url: "./server/readBooks.php"
    }).then(function (response) {
        $scope.books = response.data.books;
    });
    $scope.borrowReturn = function () {
        $scope.fields.readerId = $scope.readerId;
        $scope.fields.borrowBooksId = [];
        $scope.fields.returnBooksId = [];

        for (var i in $scope.isReturn) {
            if ($scope.isReturn[i]) {
                $scope.fields.returnBooksId.push(i);
            }
        }
        $("#borrow .book").each(function () {

        });
    };
});
