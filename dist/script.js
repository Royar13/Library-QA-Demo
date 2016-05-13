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
angular.module("library").controller("addReaderCtrl", function ($scope, $http) {
    $scope.fields = {
        id: "",
        name: "",
        city: "",
        street: "",
        maxBooks: 0,
        readerType: ""
    };
    $scope.errors = {};
    $scope.select = {maxBooks:[]};
    $scope.monthlyPay = function () {
        try {
            var pay = getReaderType($scope.fields.readerType).bookCost * $scope.fields.maxBooks;
            return pay;
        }
        catch (err) {
            return 0;
        }
    };
    function getReaderType(id) {
        for (var i in $scope.readerTypes) {
            if ($scope.readerTypes[i].id == id)
                return $scope.readerTypes[i];
        }
    }
    $http({
        method: "post",
        url: "./server/getReaderTypes.php"
    }).then(function (response) {
        $scope.select.readerTypes = response.data.readerTypes;
    });
    $http({
        method: "post",
        url: "./server/getBooksNum.php"
    }).then(function (response) {
        $scope.select.maxBooks = response.data.booksNum;
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
angular.module("library").controller("panelCtrl", function ($scope, $window, $location) {
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

    $scope.includeTopBar = function() {
         return $location.path() != "/";
    }
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
    function retrieveUser() {
        Object.keys(userService.user).forEach(function (key) {
            $scope.user[key] = userService.user[key];
        });
    }
    ;
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
angular.module("library").directive("error", function () {
    return {
    };
});
angular.module("library").directive("errors", function () {
    return {
        restrict: "A",
        scope: true,
        templateUrl: "app/directives/field/errors.html"
    };
});
angular.module("library").directive("selectField", function () {
    return {
        restrict: "A",
        scope: true,
        require: "field",
        templateUrl: "app/directives/field/selectField.html",
        controller: function ($scope, $element) {
            $scope.field = $element.attr("field-name");

            $scope.class = "";
            if ($element[0].hasAttribute("add-class")) {
                $scope.class = $element.attr("add-class");
            }
            $scope.selectName = $element.attr("options");
            var valueName = null;
            if ($element[0].hasAttribute("options-value")) {
                valueName = $element.attr("options-value");
            }
            var textName = null;
            if ($element[0].hasAttribute("options-text")) {
                textName = $element.attr("options-text");
            }
            //$scope.$watch("select", function () {
            //updateOptions();
            //});
            $scope.getOptionValue = function (option) {
                if (valueName == null)
                    return option;
                else
                    return option[valueName];
            };
            $scope.getOptionText = function (option) {
                if (textName == null)
                    return option;
                else
                    return option[textName];
            };
        }
    };
});
angular.module("library").directive("textField", function () {
    return {
        restrict: "A",
        scope: true,
        templateUrl: "app/directives/field/textField.html",
        controller: function ($scope, $element) {
            $scope.field = $element.attr("field-name");
            $scope.class = "";
            if ($element[0].hasAttribute("add-class")) {
                $scope.class = $element.attr("add-class");
            }
        }
    };
});
//# sourceMappingURL=maps/script.js.map
