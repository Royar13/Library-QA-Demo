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
            .when("/displayUsers", {
                templateUrl: "app/displayUsers/displayUsers.html",
                controller: "displayUsersCtrl"
            })
            .when("/updateUser", {
                templateUrl: "app/updateUser/updateUser.html",
                controller: "updateUserCtrl"
            })
            .when("/createUser", {
                templateUrl: "app/createUser/createUser.html",
                controller: "createUserCtrl"
            })
            .when("/updatePassword", {
                templateUrl: "app/updatePassword/updatePassword.html",
                controller: "updatePasswordCtrl"
            })
            .when("/updateSections", {
                templateUrl: "app/updateSections/updateSections.html",
                controller: "updateSectionsCtrl"
            })
            .when("/updateUserMenu", {
                templateUrl: "app/updateUser/updateUserMenu.html",
                controller: "updateUserMenuCtrl"
            })
            .when("/borrowBooks", {
                templateUrl: "app/borrowBooks/borrowBooks.html",
                controller: "borrowBooksCtrl"
            });
}).run(function ($rootScope, $location) {
    $rootScope.$on("$routeChangeSuccess", function (event, data) {
        if (data.$$route && data.$$route.controller)
            $rootScope.controllerName = data.$$route.controller;
    });
});