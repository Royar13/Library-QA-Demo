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
            url: "./server/index.php",
            data: {action: "fetchLoggedUser"}
        }).then(function (response) {
            if (response.data.success) {
                _this.updateUser(response.data.username, response.data.name);
            }
        });
    };
});