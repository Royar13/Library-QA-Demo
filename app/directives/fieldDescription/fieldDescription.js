angular.module("library").directive("fieldDescription", function () {
    return {
        restrict: "A",
        scope: {
            fieldDescription: "@"
        },
        transclude: true,
        replace: true,
        templateUrl: "app/directives/fieldDescription/fieldDescription.html",
        link: function (scope, elem, attrs) {
            var bubble;
            var sel = "input, select";

            $(elem).on("focus", sel, function () {
                var right = $(elem).find(sel).outerWidth() + 11;
                var width = Math.min(340, 550 - right);

                bubble = $("<div>").addClass("fieldDescription").text(scope.fieldDescription).css({right: right, width: width});
                $("<div>").addClass("triangle").appendTo(bubble);
                if (scope.fieldDescription != "") {
                    bubble.appendTo(elem);
                }
            });
            $(elem).on("blur", sel, function () {
                bubble.remove();
            });
        }
    };
});