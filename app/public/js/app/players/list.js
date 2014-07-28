(function() {
    "use strict";
    angular.module("ptOS").controller("players.list", ["$scope", "$interval", "Event", "Player",
        function($scope, $interval, Event, Player) {
            $scope.filters = "";
            $scope.page = 1;

            $scope.players = [];

            var queryParams = {};
            var compileFilter = function() {
                queryParams = {};
                if($scope.filters != "") {
                    $scope.filters = $scope.filters.trim();
                    if($scope.filters.indexOf(":") > -1) {
                        var parts = $scope.filters.split(":");
                        queryParams[parts[0].trim()] = parts[1].trim();
                    } else {
                        queryParams["text"] = $scope.filters;
                    }
                }
            }

            $scope.filter = function() {
                compileFilter();
                $scope.players = Player.query(queryParams);
            }
            $scope.players = Player.query(queryParams);
        }]);
})();