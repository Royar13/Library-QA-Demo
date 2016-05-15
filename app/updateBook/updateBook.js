angular.module("library").controller("updateBookCtrl", function ($scope, $http, $routeParams, $location, $route, alertify) {
    var boolSectionsFinish = false;
    $scope.editMode = false;
    var bookId = $routeParams.id;
    $scope.fields = {
        id: "",
        name: "",
        sectionId: "",
        bookcaseId: "",
        author: "",
        publisher: "",
        releaseYear: "",
        copies: ""
    };
    $scope.errors = {};
    $scope.select = {};
    $http({
        method: "post",
        url: "./dist/server/readBook.php",
        data: {id: bookId}
    }).then(function (response) {
        $scope.fields = response.data;
        $scope.fields.id = bookId;
        $scope.fields.releaseYear = Number($scope.fields.releaseYear);
        $scope.fields.copies = Number($scope.fields.copies);
        if (boolSectionsFinish)
            $scope.updateBookcases();
        boolSectionsFinish = true;

    });
    $http({
        method: "post",
        url: "./dist/server/readAuthors.php"
    }).then(function (response) {
        $scope.select.authors = response.data.authors;
    });
    $http({
        method: "post",
        url: "./dist/server/readPublishers.php"
    }).then(function (response) {
        $scope.select.publishers = response.data.publishers;
    });
    $http({
        method: "post",
        url: "./dist/server/readSections.php"
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
        $http({
            method: "post",
            url: "./dist/server/updateBook.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
                alertify.error("הקלט שהוזן אינו תקין");
            } else {
                alertify.success("הספר עודכן בהצלחה!");
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
