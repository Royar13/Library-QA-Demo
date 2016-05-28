angular.module("library").controller("updateReaderCtrl", function ($scope, $http, $routeParams, $location, $route, alertify) {
    var boolData = false;
    $scope.editMode = false;
    var readerId = $routeParams.id;
    $scope.fields = {
        action: "updateReader",
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
    };
    $scope.address = function () {
        if ($scope.fields.city != "") {
            return $scope.fields.street + ", " + $scope.fields.city;
        }
        return "";
    };
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readReader", id: readerId}
    }).then(function (response) {
        for (var i in response.data) {
            $scope.fields[i] = response.data[i];
        }
        $scope.fields.id = readerId;
        updatePay();
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readReaderTypes"}
    }).then(function (response) {
        $scope.select.readerTypes = response.data.readerTypes;
        updatePay();
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readBooksNum"}
    }).then(function (response) {
        $scope.select.maxBooks = response.data.booksNum;
    });

    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllBorrowsByReader", readerId: readerId}
    }).then(function (response) {
        $scope.borrows = response.data.borrows;
        for (var i in $scope.borrows) {
            var borrow = $scope.borrows[i];
            if (borrow.isLate == 1) {
                $scope.fine += borrow.fine;
            }
        }
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readActionsByReader", id: readerId}
    }).then(function (response) {
        $scope.actions = response.data.actions;
    });
    $scope.updateReader = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/index.php",
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
    $scope.deleteReader = function () {
        alertify.confirm("האם אתה בטוח שברצונך למחוק את הקורא \"" + $scope.fields.name + "\"?", function () {
            $scope.loading = true;
            $scope.errors = {};

            $http({
                method: "post",
                url: "./server/index.php?XDEBUG_SESSION_START=netbeans-xdebug",
                data: {action: "deleteReader", id: $scope.fields.id}
            }).then(function (response) {
                if (!response.data.success) {
                    $scope.loading = false;
                    $scope.errors = response.data.errors;
                    alertify.error("אי אפשר למחוק את הקורא");
                } else {
                    alertify.success("הקורא נמחק בהצלחה!");
                    $location.path("/");
                }
            });
        });
    };
    $scope.toggleModes = function () {
        if ($scope.editMode)
            $route.reload();
        else
            $scope.editMode = !$scope.editMode;
    };
});
