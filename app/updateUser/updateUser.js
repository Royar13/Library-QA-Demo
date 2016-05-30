angular.module("library").controller("updateUserCtrl", function ($scope, $http, $routeParams, $location, $route, alertify, userService) {
    $scope.showEditBtn = function () {
        return userService.hasPermission(12);
    };
    $scope.showDeleteBtn = function () {
        return userService.hasPermission(13);
    };
    $scope.editMode = false;
    var userId = $routeParams.id;
    $scope.fields = {
        action: "updateUser",
    };
    $scope.errors = {};
    $scope.select = {};

    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readUser", id: userId}
    }).then(function (response) {
        for (var i in response.data) {
            $scope.fields[i] = response.data[i];
        }
        $scope.fields.id = userId;
    });

    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllUserTypes"}
    }).then(function (response) {
        $scope.select.userTypes = response.data.userTypes;
    });

    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readActionsByUser", id: userId}
    }).then(function (response) {
        $scope.actions = response.data.actions;
    });

    $scope.updateUser = function () {
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
                alertify.success("המשתמש עודכן בהצלחה!");
                $scope.editMode = false;
                $scope.errors = {};
            }
        });
    };
    $scope.deleteUser = function () {
        alertify.confirm("האם אתה בטוח שברצונך למחוק את המשתמש \"" + $scope.fields.username + "\"?", function () {
            $scope.loading = true;
            $scope.errors = {};

            $http({
                method: "post",
                url: "./server/index.php",
                data: {action: "deleteUser", id: $scope.fields.id}
            }).then(function (response) {
                if (!response.data.success) {
                    $scope.loading = false;
                    $scope.errors = response.data.errors;
                    alertify.error("אי אפשר למחוק את המשתמש");
                } else {
                    alertify.success("המשתמש נמחק בהצלחה!");
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

    $scope.getUserTypeById = function (id) {
        for (var i in $scope.select.userTypes) {
            if ($scope.select.userTypes[i].id == id) {
                return $scope.select.userTypes[i];
            }
        }
    };
});
