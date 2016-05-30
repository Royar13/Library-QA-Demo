angular.module("library").controller("updateSectionsCtrl", function ($scope, $http, $route, $location, alertify) {
    $scope.fields = {
        action: "updateSections",
        createSections: [],
        updateSections: []
    };
    $scope.errors = {};

    $http({
        method: "post",
        url: "./server/index.php",
        data: {action: "readAllSections"}
    }).then(function (response) {
        for (var i in response.data.sections) {
            response.data.sections[i].bookcaseAmount = Number(response.data.sections[i].bookcaseAmount);
        }
        $scope.fields.updateSections = response.data.sections;
    });


    $scope.updateSections = function () {
        $scope.loading = true;
        $scope.errors = {};

        $http({
            method: "post",
            url: "./server/index.php",
            data: $scope.fields
        }).then(function (response) {
            $scope.loading = false;
            if (!response.data.success) {
                var obj = response.data.errors.fields;
                $scope.errors = {general: [obj[Object.keys(obj)[0]][0]]};
                alertify.error("הקלט שהוזן אינו תקין");
            } else {
                alertify.success("המדורים עודכנו בהצלחה!");
                $route.reload();
            }
        });
    };

    $scope.addSection = function () {
        $scope.fields.createSections.push({
            name: "",
            bookcaseAmount: 0
        });
    };
    $scope.removeSection = function (section) {
        for (var i in $scope.fields.createSections) {
            if ($scope.fields.createSections[i] == section) {
                $scope.fields.createSections.splice(i, 1);
                return;
            }
        }
    };
});
