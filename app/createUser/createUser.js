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
            url: "./server/index.php?XDEBUG_SESSION_START=netbeans-xdebug",
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

    $scope.getUserTypeById = function (id) {
        for (var i in $scope.select.userTypes) {
            if ($scope.select.userTypes[i].id == id) {
                return $scope.select.userTypes[i];
            }
        }
    };
});
