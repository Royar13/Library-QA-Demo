angular.module("library").controller("doctorWhoCtrl", function ($scope, $http, $routeParams) {
    $scope.year=$routeParams.year;
});
