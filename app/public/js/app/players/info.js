(function() {
    "use strict";

    angular.module("ptOS").controller("players.info", ["$scope", "$routeParams", "Player", "Event", "$interval", "$http",
        function($scope, $routeParams, Player, Event, $interval, $http) {
            $scope.events = [];
            $scope.lookupLocation = "";
            $scope.lookupCountry = "";
            var playerId = $routeParams.playerId,
                showAllPlayerNames = false,
                showAllPlayerIps = false;
            $scope.player = Player.get({ id: playerId }, function() {
                updatePlayerNamesAndIps();
                loadEvents();

                $http.get("iplookup/" + $scope.player.latest_ip)
                    .success(function(ipinfo) {
                        var parts = [];
                        if("city" in ipinfo) {
                            parts.push(ipinfo.city.names.en);
                        }
                        if("country" in ipinfo) {
                            parts.push(ipinfo.country.names.en);
                            $scope.lookupCountry = ipinfo.country.iso_code.toLowerCase();
                        }
                        if("continent" in ipinfo) {
                            parts.push(ipinfo.continent.names.en);
                        }
                        $scope.lookupLocation = parts.join(", ");
                    });
            });

            $scope.playerIps = [];
            $scope.playerNames = [];

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
                        if(e.type in $scope.eventTypes) {
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
                });
            }

            var updatePlayerNamesAndIps = function() {
                if(showAllPlayerIps || $scope.player.ips.length < 5) {
                    $scope.playerIps = $scope.player.ips;
                } else {
                    $scope.playerIps = $scope.player.ips.slice(0, 5);
                }

                if(showAllPlayerNames || $scope.player.names.length < 5) {
                    $scope.playerNames = $scope.player.names;
                } else {
                    $scope.playerNames = $scope.player.names.slice(0, 5);
                }
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
                    updatePlayerNamesAndIps();
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