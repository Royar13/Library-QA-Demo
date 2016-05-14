var app = angular.module("library", ["ngRoute", "ngAlertify", "smart-table", "angucomplete-alt"]);
app.config(function ($routeProvider) {
    $routeProvider
            .when("/", {
                templateUrl: "app/login/login.html",
                controller: "loginCtrl"
            })
            .when("/main", {
                templateUrl: "app/main/main.html",
                controller: "mainCtrl"
            })
            .when("/displayReaders", {
                templateUrl: "app/displayReaders/displayReaders.html",
                controller: "displayReadersCtrl"
            })
            .when("/addReader", {
                templateUrl: "app/addReader/addReader.html",
                controller: "addReaderCtrl"
            })
            .when("/addBook", {
                templateUrl: "app/addBook/addBook.html",
                controller: "addBookCtrl"
            });
}).run(function ($rootScope, $location) {
    $rootScope.$on("$routeChangeSuccess", function (event, data) {
        if (data.$$route && data.$$route.controller)
            $rootScope.controllerName = data.$$route.controller;
    });
});