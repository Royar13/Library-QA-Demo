angular.module("library").controller("addReaderCtrl", function ($scope, $http, alertify) {
    $scope.fields = {
        id: "",
        name: "",
        city: "",
        street: "",
        maxBooks: 0,
        readerType: ""
    };
    $scope.errors = {};
    $scope.select = {};
    $scope.monthlyPay = 0;
    $scope.$watchGroup(["fields.readerType", "fields.maxBooks"], function (newValues, oldValues, scope) {
        try {
            scope.monthlyPay = getReaderType(newValues[0]).bookCost * newValues[1];
        }
        catch (ex) {

        }
    });

    function getReaderType(id) {
        for (var i in $scope.select.readerTypes) {
            if ($scope.select.readerTypes[i].id == id)
                return $scope.select.readerTypes[i];
        }
    }
    $http({
        method: "post",
        url: "./server/readReaderTypes.php"
    }).then(function (response) {
        $scope.select.readerTypes = response.data.readerTypes;
    });
    $http({
        method: "post",
        url: "./server/readBooksNum.php"
    }).then(function (response) {
        $scope.select.maxBooks = response.data.booksNum;
    });
    $scope.addReader = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/addReader.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
                alertify.error("הקלט שהוזן אינו תקין");
            }
            else {
                alertify.success("הקורא נוסף בהצלחה!");
            }
        });
    };
});
