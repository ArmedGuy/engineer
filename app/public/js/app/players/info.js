(function() {
    "use strict";

    angular.module("ptOS").controller("players.info", ["$scope", "$routeParams", "Player", "Event", "$interval",
        function($scope, $routeParams, Player, Event, $interval) {
            $scope.events = [];

            var playerId = $routeParams.playerId;
            $scope.player = Player.get({ id: playerId }, function() {
                loadEvents();
            });
            // Player event stream
            var firstLoad = true;
            var eventQueryParams = { player_id: playerId, noPlayer: true, max: 10 };
            var loadEvents = function() {
                Event.query(eventQueryParams, function(data) {
                    data = data.reverse(); // we want it in reverse order if we are filling
                    for(var i = 0; i < data.length; i++) {
                        var e = data[i];
                        if(firstLoad != true)
                            e.isNew = true;
                        e.loaded = false;
                        e.player = $scope.player;
                        if($scope.eventTypes.indexOf(e.type) > -1) {
                            e.templateUrl = "app/public/partials/events/event-" + e.type + ".html";
                        } else {
                            e.templateUrl = "app/public/partials/events/event-default.html";
                        }
                        if($scope.events.length == 10) {
                            $scope.events.pop();
                            $scope.events.unshift(e);
                        } else {
                            $scope.events.unshift(e);
                        }
                        e.loaded = true;
                    }
                    if(firstLoad)
                        firstLoad = false;

                    $scope.$digest();
                });
            }
            var updateLoop = $interval(function() {
                for(var i in $scope.events) {
                    $scope.events[i].isNew = false;
                }
                if($scope.events.length > 0) {
                    eventQueryParams.after = $scope.events[0].id;
                } else {
                    eventQueryParams.after = 0;
                }
                Player.get({ id: playerId }, function(data) {
                    $scope.player = data;
                    loadEvents();
                    eventQueryParams.after = 0;
                });
            }, 20000);

            $scope.$on("$destroy", function() {
                $interval.cancel(updateLoop);
            });
        }]);
})();