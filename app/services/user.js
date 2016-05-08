angular.module("library").service("userService", function ($http, $location) {
    this.updateUser = function (username, name) {
        this.username = username;
        this.name = name;
    };
    this.getUser = function () {
        var _this = this;
        return $http({
            method: "post",
            url: "./server/login.php"
        }).then(function (response) {
            if (response.data.success) {
                _this.username = response.data.username;
                _this.name = response.data.name;
                callback();
            } else {
                $location.path("/");
            }
        });
    };
});