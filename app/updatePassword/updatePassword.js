angular.module("library").controller("updatePasswordCtrl", function ($scope, $http, $location, alertify) {
    $scope.fields = {
        action: "updatePassword"
    };
    $scope.errors = {};
    $scope.updatePassword = function () {
        $scope.loading = true;
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
                alertify.success("הסיסמא עודכנה בהצלחה!");
                $location.path("/");
            }
        });
    };
});