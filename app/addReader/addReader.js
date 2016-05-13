angular.module("library").controller("addReaderCtrl", function ($scope, $http) {
    $scope.fields = {
        id: "",
        name: "",
        city: "",
        street: "",
        maxBooks: 0,
        readerType: ""
    };
    $scope.errors = {};
    $scope.select = {maxBooks:[]};
    $scope.monthlyPay = function () {
        try {
            var pay = getReaderType($scope.fields.readerType).bookCost * $scope.fields.maxBooks;
            return pay;
        }
        catch (err) {
            return 0;
        }
    };
    function getReaderType(id) {
        for (var i in $scope.readerTypes) {
            if ($scope.readerTypes[i].id == id)
                return $scope.readerTypes[i];
        }
    }
    $http({
        method: "post",
        url: "./server/getReaderTypes.php"
    }).then(function (response) {
        $scope.select.readerTypes = response.data.readerTypes;
    });
    $http({
        method: "post",
        url: "./server/getBooksNum.php"
    }).then(function (response) {
        $scope.select.maxBooks = response.data.booksNum;
    });
});
