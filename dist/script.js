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
            });
}).run(function ($rootScope, $location) {
    $rootScope.$on("$routeChangeSuccess", function (event, data) {
        if (data.$$route && data.$$route.controller)
            $rootScope.controllerName = data.$$route.controller;
    });
});
angular.module("library").controller("addBookCtrl", function ($scope, $http, $location, alertify) {

    $scope.fields = {
        action: "createBook",
        copies: 1
    };
    $scope.select = {};
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllAuthors"}
    }).then(function (response) {
        $scope.select.authors = response.data.authors;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllPublishers"}
    }).then(function (response) {
        $scope.select.publishers = response.data.publishers;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllSections"}
    }).then(function (response) {
        $scope.select.sections = response.data.sections;

    });
    $scope.$watch("fields.sectionId", function () {
        $scope.updateBookcases();
    });
    $scope.updateBookcases = function () {
        try {
            $scope.select.bookcases = new Array();
            for (var i = 0; i < getSectionById($scope.fields.sectionId).bookcaseAmount; i++) {
                $scope.select.bookcases[i] = i + 1;
            }
        } catch (ex) {

        }
    };
    $scope.addBook = function () {
        $scope.fields.author = $("#author_value").val();
        $scope.fields.publisher = $("#publisher_value").val();

        $scope.loading = true;
        $scope.errors = {};
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            if (!response.data.success) {
                $scope.loading = false;
                $scope.errors = response.data.errors;
                alertify.error("הקלט שהוזן אינו תקין");
            } else {
                alertify.success("הספר נוסף בהצלחה!");
                $location.path("/updateBook").search({id: response.data.id});
            }
        });
    };
    function getSectionById(id) {
        for (var i in $scope.select.sections) {
            var section = $scope.select.sections[i];
            if (section.id == id)
                return section;
        }
    }
});

angular.module("library").controller("addReaderCtrl", function ($scope, $http, $location, alertify) {
    $scope.fields = {
        action: "createReader"
    };
    $scope.select = {};
    $scope.monthlyPay = 0;
    $scope.$watchGroup(["fields.readerType", "fields.maxBooks"], function (newValues, oldValues, scope) {
        try {
            scope.monthlyPay = getReaderType(newValues[0]).bookCost * newValues[1];
        }
        catch (ex) {

        }
    });

    function getReaderType(id) {
        for (var i in $scope.select.readerTypes) {
            if ($scope.select.readerTypes[i].id == id)
                return $scope.select.readerTypes[i];
        }
    }
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readReaderTypes"}
    }).then(function (response) {
        $scope.select.readerTypes = response.data.readerTypes;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readBooksNum"}
    }).then(function (response) {
        $scope.select.maxBooks = response.data.booksNum;
    });
    $scope.addReader = function () {
        $scope.loading = true;
        $scope.errors = {};
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            if (!response.data.success) {
                $scope.loading = false;
                $scope.errors = response.data.errors;
                alertify.error("הקלט שהוזן אינו תקין");
            }
            else {
                alertify.success("הקורא נוסף בהצלחה!");
                $location.path("/updateReader").search({id: $scope.fields.id});
            }
        });
    };
});

angular.module("library").controller("borrowBooksCtrl", function ($scope, $http, $location, $routeParams, alertify) {
    $scope.readerId = $routeParams.id;
    $scope.isReturn = [];
    $scope.borrows = [];
    $scope.allowedBooksNum = function () {
        try {
            return Number($scope.maxBooks) + returnedAmount() - $scope.borrowedBooks.length;
        }
        catch ($ex) {
            return 0;
        }
    };

    $scope.fields = {
        action: "borrowReturnBooks"
    };
    $scope.select = {};
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readReader", id: $scope.readerId}
    }).then(function (response) {
        $scope.readerName = response.data.name;
        $scope.maxBooks = response.data.maxBooks;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readBorrowsByReaderForDisplay", readerId: $scope.readerId}
    }).then(function (response) {
        $scope.borrowedBooks = response.data.borrows;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllBooksForBorrow"}
    }).then(function (response) {
        $scope.books = response.data.books;
    });
    $scope.borrowReturn = function () {
        $scope.loading = true;
        $scope.errors = {};
        $scope.fields.readerId = $scope.readerId;
        $scope.fields.borrowBooksId = [];
        $scope.fields.returnBooksId = [];

        for (var i in $scope.isReturn) {
            if ($scope.isReturn[i]) {
                $scope.fields.returnBooksId.push(i);
            }
        }
        for (var i in $scope.borrows) {
            $scope.fields.borrowBooksId.push($scope.borrows[i].originalObject.id);
        }
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            if (!response.data.success) {
                $scope.loading = false;
                alertify.error("קלט לא תקין");
                $scope.errors = response.data.errors;
            }
            else {
                alertify.success("הספרים הוחזרו/הושאלו בהצלחה!");
                $location.path("/updateReader").search({id: $scope.readerId});

            }
        });
    };
    $scope.getBorrowLength = function () {
        var len = Math.min($scope.borrows.length + 1, $scope.allowedBooksNum());
        var arr = new Array();
        for (var i = 0; i < len; i++) {
            arr[i] = "";
        }
        return arr;
    };
    function returnedAmount() {
        var c = 0;
        for (var i in $scope.isReturn) {
            if ($scope.isReturn[i]) {
                c++;
            }
        }
        return c;
    }
    ;
    $scope.switchCb = function ($event, key) {
        if ($event.target.className == "cbInit") {
            $scope.isReturn[key] = !$scope.isReturn[key];
        }
    };
});

angular.module("library").controller("borrowBooksMenuCtrl", function ($scope, $http, $location) {
    $scope.fields = {
        action: "readerExists"
    };

    $scope.searchReader = function () {
        $scope.loading = true;
        $scope.errors = {};
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            if (!response.data.success) {
                $scope.loading = false;
                $scope.errors = response.data.errors;
            } else {
                $location.path("/borrowBooks").search({id: $scope.fields.id});
            }
        });
    };
});

angular.module("library").controller("displayBooksCtrl", function ($scope, $http) {
    $scope.books = [];
    $scope.quantity = 50;
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllBooks"}
    }).then(function (response) {
        $scope.books = response.data.books;
    });
});
angular.module("library").filter('dateToISO', function () {
    return function (input) {
        return new Date(input).toISOString();
    };
});
app.filter('unique', function () {
    return function (arr, field) {
        var o = {}, i, l = arr.length, r = [];
        for (i = 0; i < l; i += 1) {
            o[arr[i][field]] = arr[i];
        }
        for (i in o) {
            r.push(o[i]);
        }
        return r;
    };
});
angular.module("library").controller("displayReadersCtrl", function ($scope, $http) {
    $scope.readers = [];
    $scope.quantity = 50;
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllReaders"}
    }).then(function (response) {
        $scope.readers = response.data.readers;
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
        action: "login"
    };
    $scope.errors = {};
    $scope.login = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/index.php",
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
angular.module("library").controller("panelCtrl", function ($scope, $window, $location, alertify) {
    alertify.logPosition("top right");

    var bgRatio = 1.67;
    var bgWidth = $(window).height() * bgRatio;
    var leftMargin = ($(window).width() - bgWidth) / 2;
    $scope.panelStyle = {};
    $scope.panelStyle.right = Math.max(leftMargin + 20, 20);
    //$scope.panelStyle.width = bgWidth * 0.7;
    //$scope.panelStyle.height = $(window).height() * 0.8;

    $scope.includeTopBar = function () {
        return $location.path() != "/";
    }
    $scope.loading = false;
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
            url: "./server/index.php",
            data: {action: "fetchLoggedUser"}
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
            url: "./server/index.php",
            data: {action: "disconnect"}
        }).then(function () {
            userService.user = null;
            $location.path("/");
        });
    };
});
angular.module("library").controller("updateBookCtrl", function ($scope, $http, $routeParams, $location, $route, alertify) {
    var boolSectionsFinish = false;
    $scope.editMode = false;
    var bookId = $routeParams.id;
    $scope.fields = {
        action: "updateBook"
    };
    $scope.select = {};
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readBook", id: bookId}
    }).then(function (response) {
        for (var i in response.data) {
            $scope.fields[i] = response.data[i];
        }
        $scope.fields.id = bookId;
        $scope.fields.releaseYear = Number($scope.fields.releaseYear);
        $scope.fields.copies = Number($scope.fields.copies);
        if (boolSectionsFinish)
            $scope.updateBookcases();
        boolSectionsFinish = true;

    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllAuthors"}
    }).then(function (response) {
        $scope.select.authors = response.data.authors;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllPublishers"}
    }).then(function (response) {
        $scope.select.publishers = response.data.publishers;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllSections"}
    }).then(function (response) {
        $scope.select.sections = response.data.sections;
        if (boolSectionsFinish)
            $scope.updateBookcases();
        boolSectionsFinish = true;
    });
    $scope.$watch("fields.sectionId", function () {
        $scope.updateBookcases();
    });
    $scope.updateBookcases = function () {
        try {
            $scope.select.bookcases = new Array();
            for (var i = 0; i < $scope.getSectionById($scope.fields.sectionId).bookcaseAmount; i++) {
                $scope.select.bookcases[i] = i + 1;
            }
        } catch (ex) {

        }
    };
    $scope.getSectionById = function (id) {
        for (var i in $scope.select.sections) {
            var section = $scope.select.sections[i];
            if (section.id == id)
                return section;
        }
    };
    $scope.updateBook = function () {
        $scope.fields.author = $("#author_value").val();
        $scope.fields.publisher = $("#publisher_value").val();

        $scope.loading = true;
        $scope.errors = {};

        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
                alertify.error("הקלט שהוזן אינו תקין");
            } else {
                alertify.success("הספר עודכן בהצלחה!");
                $scope.editMode = false;
            }
        });
    };
    $scope.toggleModes = function () {
        if ($scope.editMode)
            $route.reload();
        else
            $scope.editMode = !$scope.editMode;
    };
});

angular.module("library").controller("updateBookMenuCtrl", function ($scope, $http, $location) {
    $scope.fields = {
        action: "bookExists"
    };

    $scope.searchBook = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
            } else {
                $location.path("/updateBook").search({id: $scope.fields.id});
            }
        });
    };
});

angular.module("library").controller("updateReaderCtrl", function ($scope, $http, $routeParams, $location, $route, alertify) {
    var boolData = false;
    $scope.editMode = false;
    var readerId = $routeParams.id;
    $scope.fields = {
        action: "updateReader",
        joinDate: 0
    };
    $scope.fine = 0;
    $scope.errors = {};
    $scope.select = {};
    $scope.monthlyPay = 0;
    $scope.$watchGroup(["fields.readerType", "fields.maxBooks"], function (newValues, oldValues, scope) {
        updatePay();
    });
    function updatePay() {
        try {
            $scope.monthlyPay = $scope.getReaderType($scope.fields.readerType).bookCost * $scope.fields.maxBooks;
        }
        catch (ex) {

        }
    }

    $scope.getReaderType = function (id) {
        for (var i in $scope.select.readerTypes) {
            if ($scope.select.readerTypes[i].id == id)
                return $scope.select.readerTypes[i];
        }
    };
    $scope.address = function () {
        if ($scope.fields.city != "") {
            return $scope.fields.street + ", " + $scope.fields.city;
        }
        return "";
    };
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readReader", id: readerId}
    }).then(function (response) {
        for (var i in response.data) {
            $scope.fields[i] = response.data[i];
        }
        $scope.fields.id = readerId;
        updatePay();
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readReaderTypes"}
    }).then(function (response) {
        $scope.select.readerTypes = response.data.readerTypes;
        updatePay();
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readBooksNum"}
    }).then(function (response) {
        $scope.select.maxBooks = response.data.booksNum;
    });

    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllBorrowsByReader", readerId: readerId}
    }).then(function (response) {
        $scope.borrows = response.data.borrows;
        for (var i in $scope.borrows) {
            var borrow = $scope.borrows[i];
            if (borrow.isLate == 1) {
                $scope.fine += borrow.fine;
            }
        }
    });
    $scope.updateReader = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
                alertify.error("הקלט שהוזן אינו תקין");
            } else {
                alertify.success("הקורא עודכן בהצלחה!");
                $scope.editMode = false;
                $scope.errors = {};
            }
        });
    };
    $scope.toggleModes = function () {
        if ($scope.editMode)
            $route.reload();
        else
            $scope.editMode = !$scope.editMode;
    };
});

angular.module("library").controller("updateReaderMenuCtrl", function ($scope, $http, $location) {
    $scope.fields = {
        action: "readerExists"
    };

    $scope.searchReader = function () {
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
            } else {
                $location.path("/updateReader").search({id: $scope.fields.id});
            }
        });
    };
});

angular.module("library").directive("btnsMenu", function () {
    return {
        restrict: "A",
        scope: {},
        templateUrl: "app/directives/btnsMenu/btnsMenu.html",
        controller: function ($scope, $location, $window) {
            $scope.back = function () {
                $window.history.back();
            };
            $scope.home = function () {
                $location.path("/main");
            };
        }
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
angular.module("library").directive("generalErrors", function () {
    return {
        restrict: "A",
        scope: true,
        templateUrl: "app/directives/field/generalErrors.html"
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
            $scope.fieldType = "text";
            if ($element[0].hasAttribute("field-type")) {
                $scope.fieldType = $element.attr("field-type");
            }
        }
    };
});
//# sourceMappingURL=maps/script.js.map
