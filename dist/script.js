var app = angular.module("library", ["ngRoute"]);
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
            .when("/addReader", {
                templateUrl: "app/addReader/addReader.html",
                controller: "addReaderCtrl"
            });
}).run(function ($rootScope, $location) {
    $rootScope.$on("$routeChangeSuccess", function (event, data) {
        if (data.$$route && data.$$route.controller)
            $rootScope.controllerName = data.$$route.controller;
    });
});
angular.module("library").controller("loginCtrl", function ($scope, $http, userService, $location) {
    var request = userService.getUser();
    if (request === true) {
        $location.path("/main");
    } else {
        request.then(function () {
            if (userService.user != null)
                $location.path("/main");
        });
    }
    $scope.fields = {
        username: "",
        password: ""
    };
    $scope.errors = {};
    $scope.loading = false;
    $scope.login = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/login.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (response.data.success) {
                userService.updateUser(response.data.username, response.data.name);
                $location.path("/main");
            } else {
                $scope.errors = response.data.errors;
            }
        });
    };
});
angular.module("library").controller("mainCtrl", function ($scope) {
});
angular.module("library").controller("panelCtrl", function ($scope, $window) {
    var bgRatio = 1.67;
    var bgWidth = $(window).height() * bgRatio;
    var leftMargin = ($(window).width() - bgWidth) / 2;
    $scope.panelStyle = {};
    $scope.panelStyle.right = Math.max(leftMargin + 20, 20);
    //$scope.panelStyle.width = bgWidth * 0.7;
    //$scope.panelStyle.height = $(window).height() * 0.8;

    $scope.back = function () {
        $window.history.back();
    };
});
angular.module("library").service("userService", function ($http, $location) {
    this.updateUser = function (username, name) {
        this.user = {username: username, name: name};
    };
    this.getUser = function () {
        if (this.user != null) {
            return true;
        }
        var _this = this;
        return $http({
            method: "post",
            url: "./server/login.php"
        }).then(function (response) {
            if (response.data.success) {
                _this.updateUser(response.data.username, response.data.name);
            }
        });
    };
});
angular.module("library").controller("topBarCtrl", function ($scope, $http, $location, userService) {
    $scope.user = {};
    var retrieveUser = function () {
        Object.keys(userService.user).forEach(function (key) {
            $scope.user[key] = userService.user[key];
        });
    };
    var request = userService.getUser();
    if (request === true) {
        retrieveUser();
    } else {
        request.then(function () {
            if (userService.user != null)
                retrieveUser();
            else
                $location.path("/");
        });
    }
    $scope.disconnect = function () {
        $http({
            method: "post",
            url: "./server/disconnect.php"
        }).then(function () {
            userService.user = null;
            $location.path("/");
        });
    };
});
angular.module("library").controller("addReaderCtrl", function ($scope) {

});

//# sourceMappingURL=maps/script.js.map
