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
