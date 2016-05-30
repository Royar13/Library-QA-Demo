angular.module("library").service("userService", function ($http, $location, $q) {
    this.user = null;
    this.userRequest = null;
    this.updateUser = function (user) {
        this.user = user;
    };
    this.disconnect = function () {
        var _this = this;
        $http({
            method: "post",
            url: "./server/index.php",
            data: {action: "disconnect"}
        }).then(function () {
            _this.updateUser(null);
            _this.userRequest = null;
            $location.path("/");
        });
    };
    this.getUser = function () {
        var deferred = $q.defer();

        if (this.user != null) {
            deferred.resolve(this.user);
        }
        else {
            if (this.userRequest == null) {
                this.userRequest = $http({
                    method: "post",
                    url: "./server/index.php",
                    data: {action: "fetchLoggedUser"}
                });
            }
            var _this = this;
            this.userRequest.then(function (response) {
                if (response.data.success) {
                    _this.updateUser(response.data);
                    deferred.resolve(_this.user);
                }
                else {
                    deferred.reject();
                }
            }, function () {
                deferred.reject();
            });
        }
        return deferred.promise;
    };

    this.hasPermission = function (id) {
        if (this.user == null)
            return false;
        var permissions = this.user.permissionsArr;
        for (var i in permissions) {
            if (permissions[i] == id)
                return true;
        }
        return false;
    };
});