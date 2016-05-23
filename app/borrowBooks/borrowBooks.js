angular.module("library").controller("borrowBooksCtrl", function ($scope, $http, $location, $routeParams, alertify) {
    $scope.readerId = $routeParams.id;
    $scope.isReturn = [];
    $scope.borrows = [];
    $scope.allowedBooksNum = function () {
        try {
            return Number($scope.maxBooks) + returnedAmount() - $scope.borrowedBooks.length;
        }
        catch ($ex) {
            return 0;
        }
    };

    $scope.fields = {
        action: "borrowReturnBooks"
    };
    $scope.select = {};
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readReader", id: $scope.readerId}
    }).then(function (response) {
        $scope.readerName = response.data.name;
        $scope.maxBooks = response.data.maxBooks;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readBorrowsByReaderForDisplay", readerId: $scope.readerId}
    }).then(function (response) {
        $scope.borrowedBooks = response.data.borrows;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllBooksForBorrow"}
    }).then(function (response) {
        $scope.books = response.data.books;
    });
    $scope.borrowReturn = function () {
        $scope.loading = true;
        $scope.errors = {};
        $scope.fields.readerId = $scope.readerId;
        $scope.fields.borrowBooksIds = [];
        $scope.fields.returnBooksIds = [];

        for (var i in $scope.isReturn) {
            if ($scope.isReturn[i]) {
                $scope.fields.returnBooksIds.push(i);
            }
        }
        for (var i in $scope.borrows) {
            $scope.fields.borrowBooksIds.push($scope.borrows[i].originalObject.id);
        }
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            if (!response.data.success) {
                $scope.loading = false;
                alertify.error("קלט לא תקין");
                $scope.errors = response.data.errors;
            }
            else {
                alertify.success("הספרים הוחזרו/הושאלו בהצלחה!");
                $location.path("/updateReader").search({id: $scope.readerId});

            }
        });
    };
    $scope.getBorrowLength = function () {
        var len = Math.min($scope.borrows.length + 1, $scope.allowedBooksNum());
        var arr = new Array();
        for (var i = 0; i < len; i++) {
            arr[i] = "";
        }
        return arr;
    };
    function returnedAmount() {
        var c = 0;
        for (var i in $scope.isReturn) {
            if ($scope.isReturn[i]) {
                c++;
            }
        }
        return c;
    }
    ;
    $scope.switchCb = function ($event, key) {
        if ($event.target.className == "cbInit") {
            $scope.isReturn[key] = !$scope.isReturn[key];
        }
    };
});
