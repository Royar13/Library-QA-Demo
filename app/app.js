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
            .when("/displayBooks", {
                templateUrl: "app/displayBooks/displayBooks.html",
                controller: "displayBooksCtrl"
            })
            .when("/addBook", {
                templateUrl: "app/addBook/addBook.html",
                controller: "addBookCtrl"
            })
            .when("/updateBook", {
                templateUrl: "app/updateBook/updateBook.html",
                controller: "updateBookCtrl"
            })
            .when("/updateBookMenu", {
                templateUrl: "app/updateBook/updateBookMenu.html",
                controller: "updateBookMenuCtrl"
            })

            .when("/updateReader", {
                templateUrl: "app/updateReader/updateReader.html",
                controller: "updateReaderCtrl"
            })
            .when("/updateReaderMenu", {
                templateUrl: "app/updateReader/updateReaderMenu.html",
                controller: "updateReaderMenuCtrl"
            })
            .when("/borrowBooksMenu", {
                templateUrl: "app/borrowBooks/borrowBooksMenu.html",
                controller: "borrowBooksMenuCtrl"
            })
            .when("/borrowBooks", {
                templateUrl: "app/borrowBooks/borrowBooks.html",
                controller: "borrowBooksCtrl"
            }).when("/doctorWho", {
                templateUrl: "app/bugs/doctorWho.html",
                controller: "doctorWhoCtrl"
            });
}).run(function ($rootScope, $location) {
    $rootScope.$on("$routeChangeSuccess", function (event, data) {
        if (data.$$route && data.$$route.controller)
            $rootScope.controllerName = data.$$route.controller;
    });
});