angular.module("library").controller("updateBookCtrl", function ($scope, $http, $routeParams, $location, $route, alertify, userService) {
    var boolSectionsFinish = false;
    $scope.showEditBtn = function () {
        return userService.hasPermission(7);
    };
    $scope.showDeleteBtn = function () {
        return userService.hasPermission(8);
    };
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
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllBorrowsByBook", bookId: bookId}
    }).then(function (response) {
        $scope.borrows = response.data.borrows;
    });
    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readActionsByBook", id: bookId}
    }).then(function (response) {
        $scope.actions = response.data.actions;
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
    $scope.deleteBook = function () {
        alertify.confirm("האם אתה בטוח שברצונך למחוק את הספר \"" + $scope.fields.name + "\"?", function () {
            $scope.loading = true;
            $scope.errors = {};
            $http({
                method: "post",
                url: "./server/index.php",
                data: {action: "deleteBook", id: $scope.fields.id}
            }).then(function (response) {
                if (!response.data.success) {
                    $scope.loading = false;
                    $scope.errors = response.data.errors;
                    alertify.error("אי אפשר למחוק את הספר");
                } else {
                    alertify.success("הספר נמחק בהצלחה!");
                    $location.path("/");
                }
            });
        });
    };
    $scope.toggleModes = function () {
        if ($scope.editMode)
            $route.reload();
        else
            $scope.editMode = !$scope.editMode;
    };
});
