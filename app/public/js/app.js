var ptOSApp = angular.module("ptOS", [
    "ngRoute",
    "ngResource"
]);

angular.module("ptOS").filter("timeAgo", function() {
    return function(dateString) {
        return moment(dateString).fromNow();
    }
});

