angular.module("library").controller("createUserCtrl", function ($scope, $http, $location, alertify) {
    $scope.fields = {
        action: "createUser"
    };
    $scope.select = {};

    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllUserTypes"}
    }).then(function (response) {
        $scope.select.userTypes = response.data.userTypes;
    });

    $scope.createUser = function () {
        $scope.loading = true;
        $scope.errors = {};
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            if (!response.data.success) {
                $scope.loading = false;
                $scope.errors = response.data.errors;
                alertify.error("הקלט שהוזן אינו תקין");
            }
            else {
                alertify.success("המשתמש נוצר בהצלחה!");
                $location.path("/");
            }
        });
    };
});
