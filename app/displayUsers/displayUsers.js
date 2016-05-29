angular.module("library").controller("displayUsersCtrl", function ($scope, $http) {
    $scope.users = [];
    $scope.quantity = 50;
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllUsers"}
    }).then(function (response) {
        $scope.users = response.data.users;
    });
});