var app = angular.module("library", []);
app.config(function ($routeProvider) {
    $routeProvider
            .when('/', {
                templateUrl: 'main/main.html',
                controller: 'mainCtrl'
            })
            .when('/contact', {
                templateUrl: 'pages/contact.html',
                controller: 'contactController'
            });
});