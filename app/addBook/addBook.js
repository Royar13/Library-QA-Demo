angular.module("library").controller("addBookCtrl", function ($scope, $http, $location, alertify) {

    $scope.fields = {
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
        url: "./server/readAuthors.php"
    }).then(function (response) {
        $scope.select.authors = response.data.authors;
    });
    $http({
        method: "post",
        url: "./server/readPublishers.php"
    }).then(function (response) {
        $scope.select.publishers = response.data.publishers;
    });
    $http({
        method: "post",
        url: "./server/readSections.php"
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
    var cEmpty = 0;
    $scope.addBook = function () {
        $scope.fields.author = $("#author_value").val();
        $scope.fields.publisher = $("#publisher_value").val();
        //bug
        var boolEmpty = true;
        for (var i in $scope.fields) {
            if ($scope.fields[i] != "") {
                boolEmpty = false;
                break;
            }
        }
        if (boolEmpty) {
            cEmpty++;
        }
        if (cEmpty == 2) {
            $scope.fields = {
                name: "מדריך הטרמפיסט לגלקסיה",
                sectionId: "1",
                bookcaseId: "1",
                author: "דאגלס אדמס",
                publisher: "",
                releaseYear: "1979",
                copies: "5"
            };
        }
        if ($scope.fields.releaseYear != "" && !isNaN($scope.fields.releaseYear) && $scope.fields.releaseYear < 1500) {
            $location.path("/doctorWho").search({year: $scope.fields.releaseYear});
            return;
        }
        $scope.loading = true;
        $http({
            method: "post",
            url: "./server/addBook.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                $scope.errors = response.data.errors;
                alertify.error("הקלט שהוזן אינו תקין");
            } else {
                alertify.success("הספר נוסף בהצלחה!");
                if (cEmpty == 2) {
                    alertify.delay(0).log("A towel is about the most massively useful thing an interstellar hitchhiker can have");
                }
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
