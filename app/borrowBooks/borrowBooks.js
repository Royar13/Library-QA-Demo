angular.module("library").controller("borrowBooksCtrl", function ($scope, $http, $routeParams, alertify) {
    $scope.readerId = $routeParams.id;
    $scope.isReturn = [];
    $scope.borrows = [];
    $scope.allowedBooksNum = 2;

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
        $scope.borrowedBooks = response.data.borrows;
    });
    $http({
        method: "post",
        url: "./server/readBooksBorrowAPI.php"
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
        for (var i in $scope.borrows) {
            $scope.fields.borrowBooksId.push($scope.borrows[i].description.id);
        }
    };
    $scope.getBorrowLength = function () {
        var len = Math.min($scope.borrows.length + 1, $scope.allowedBooksNum);
        return new Array(len);
    };
});
