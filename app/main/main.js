angular.module("library").controller("mainCtrl", function ($scope, userService) {
    $scope.showReadReader = function () {
        return userService.hasPermission(1);
    };
    $scope.showCreateReader = function () {
        return userService.hasPermission(2);
    };
    $scope.showBorrowBook = function () {
        return userService.hasPermission(9);
    };
    $scope.showReadBook = function () {
        return userService.hasPermission(5);
    };
    $scope.showCreateBook = function () {
        return userService.hasPermission(6);
    };
    $scope.showUpdateSections = function () {
        return userService.hasPermission(14);
    };
    $scope.showUpdatePersonalUser = function () {
        return userService.hasPermission(17);
    };
    $scope.showReadUser = function () {
        return userService.hasPermission(10);
    };
    $scope.showCreateUser = function () {
        return userService.hasPermission(11);
    };
    $scope.showReadPermissions = function () {
        return userService.hasPermission(15);
    };
    $scope.showBooksTable = function () {
        return userService.hasPermission(18);
    };
});