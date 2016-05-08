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
            });
});
app.run(function ($rootScope) {
    $rootScope.$on("$routeChangeSuccess", function (event, data) {
        if (data.$$route && data.$$route.controller)
            $rootScope.controllerName = data.$$route.controller;
    });
});
angular.module("library").controller("loginCtrl", function ($scope, $http, userService, $location) {
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
angular.module("library").controller("panelCtrl", function ($scope) {
    var bgRatio = 1.67;
    var bgWidth = $(window).height() * bgRatio;
    var leftMargin = ($(window).width() - bgWidth) / 2;
    $scope.panelStyle = {};
    $scope.panelStyle.right = Math.max(leftMargin + 20, 20);
    //$scope.panelStyle.width = bgWidth * 0.7;
    //$scope.panelStyle.height = $(window).height() * 0.8;
});
angular.module("library").service("userService", function ($http, $location) {
    this.updateUser = function (username, name) {
        this.username = username;
        this.name = name;
    };
    this.getUser = function () {
        if (this.username != null) {
            return true;
        }
        var _this = this;
        return $http({
            method: "post",
            url: "./server/login.php"
        }).then(function (response) {
            if (response.data.success) {
                _this.username = response.data.username;
                _this.name = response.data.name;
            } else {
                $location.path("/");
            }
        });
    };
});
angular.module("library").controller("topBarCtrl", function ($scope, $http, $location, userService) {
    var retrieveUser = function () {
        $scope.username = userService.username;
        $scope.name = userService.name;
    };
    var request = userService.getUser();
    if (request === true) {
        retrieveUser();
    }
    else {
        request.then(function () {
            retrieveUser();
        });
    }
    $scope.disconnect = function () {
        $http({
            method: "post",
            url: "./server/disconnect.php"
        }).then(function (response) {
            $location.path("/");
        });
    };
});
//# sourceMappingURL=maps/script.js.map
