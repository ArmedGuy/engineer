(function() {
    "use strict";
    angular.module("ptOS").controller("events.list", ["$scope", "$interval", "Event",
        function($scope, $interval, Event) {

            $scope.filters = "";
            $scope.page = 1;

            $scope.events = [];

            var firstLoad = true;
            var queryParams = {};
            var compileFilter = function() {
                queryParams = {};
                if($scope.filters != "") {
                    $scope.filters = $scope.filters.trim();
                    if($scope.filters.indexOf(":") > -1) {
                        var parts = $scope.filters.split(":");
                        queryParams[parts[0].trim()] = parts[1].trim();
                    }
                }
            }

            $scope.filter = function() {
                compileFilter();
                $scope.events = [];
                firstLoad = true;
                loadEvents();
            }

            var loadEvents = function() {
                Event.query(queryParams, function(data) {
                    data = data.reverse(); // we want it in reverse order if we are filling
                    for(var i = 0; i < data.length; i++) {
                        var e = data[i];
                        if(firstLoad != true)
                            e.isNew = true;
                        e.loaded = false;
                        if(e.type in $scope.eventTypes) {
                            e.templateUrl = "app/public/partials/events/event-" + e.type + ".html";
                        } else {
                            e.templateUrl = "app/public/partials/events/event-default.html";
                        }
                        if($scope.events.length == 30) {
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

            var updateLoop = $interval(function() {
                for(var i in $scope.events) {
                    $scope.events[i].isNew = false;
                }
                if($scope.events.length > 0) {
                    queryParams.after = $scope.events[0].id;
                } else {
                    queryParams.after = 0;
                }
                loadEvents();
                queryParams.after = 0;
            }, 10000);


            $scope.$on("$destroy", function() {
                $interval.cancel(updateLoop);
            });

            loadEvents();
        }]);
})();
