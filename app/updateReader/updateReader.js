angular.module("library").controller("updateReaderCtrl", function ($scope, $http, $routeParams, $location, $route, alertify) {
    var boolData = false;
    $scope.editMode = false;
    var readerId = $routeParams.id;
    $scope.fields = {
        id: "",
        name: "",
        city: "",
        street: "",
        maxBooks: 0,
        readerType: "",
        joinDate: 0
    };
    $scope.fine = 0;
    $scope.errors = {};
    $scope.select = {};
    $scope.monthlyPay = 0;
    $scope.$watchGroup(["fields.readerType", "fields.maxBooks"], function (newValues, oldValues, scope) {
        updatePay();
    });
    function updatePay() {
        try {
            $scope.monthlyPay = $scope.getReaderType($scope.fields.readerType).bookCost * $scope.fields.maxBooks;
        }
        catch (ex) {

        }
    }

    $scope.getReaderType = function (id) {
        for (var i in $scope.select.readerTypes) {
            if ($scope.select.readerTypes[i].id == id)
                return $scope.select.readerTypes[i];
        }
    }
    $scope.address = function () {
        if ($scope.fields.city != "") {
            return $scope.fields.street + ", " + $scope.fields.city;
        }
        return "";
    };
    $http({
        method: "post",
        url: "./server/readReader.php",
        data: {id: readerId}
    }).then(function (response) {
        $scope.fields = response.data;
        $scope.fields.id = readerId;
        updatePay();
    });
    $http({
        method: "post",
        url: "./server/readReaderTypes.php"
    }).then(function (response) {
        $scope.select.readerTypes = response.data.readerTypes;
        updatePay();
    });
    $http({
        method: "post",
        url: "./server/readBooksNum.php"
    }).then(function (response) {
        $scope.select.maxBooks = response.data.booksNum;
    });

    $http({
        method: "post",
        url: "./server/readAllReaderBorrows.php",
        data: {readerId: readerId}
    }).then(function (response) {
        $scope.borrows = response.data.borrows;
        for (var i in $scope.borrows) {
            var borrow = $scope.borrows[i];
            if (borrow.isLate == 1) {
                $scope.fine += borrow.fine;
            }
        }
    });
    $scope.updateReader = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/updateReader.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
                alertify.error("הקלט שהוזן אינו תקין");
            } else {
                alertify.success("הקורא עודכן בהצלחה!");
                $scope.editMode = false;
                $scope.errors = {};
            }
        });
    };
    $scope.toggleModes = function () {
        if ($scope.editMode)
            $route.reload();
        else
            $scope.editMode = !$scope.editMode;
    };
});
