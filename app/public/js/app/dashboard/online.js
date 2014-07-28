(function() {
    "use strict";
    angular.module("ptOS").controller("dashboard.online", ["$scope", "$http",
    function($scope, $http) {
        $http.get("players/online.json")
            .success(function(onlines) {
                $scope.onlinePlayers = onlines;
            });
    }]);
})();