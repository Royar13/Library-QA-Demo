angular.module("library").controller("addBookCtrl", function ($scope, $http, alertify) {
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
});
