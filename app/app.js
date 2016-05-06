var app = angular.module("library", ["ngRoute"]);
app.config(function ($routeProvider) {
    $routeProvider
            .when("/", {
                templateUrl: "app/main/main.html",
                controller: "mainCtrl"
            })
            .when("/contact", {
                templateUrl: "pages/contact.html",
                controller: "contactController"
            });
});