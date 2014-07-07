angular.module("ptOS").controller("IndexController", ["$scope", "$http", function($scope, $http) {

}]);


angular.module("ptOS").controller("SiteController", ["$scope", "$http", "$timeout", function($scope, $http, $timeout) {

    $scope.eventTypes = [];
    $http.get("app/public/event-types.json").success(function(data) {
        $scope.eventTypes = data;
        $timeout(function() {
            $scope.$broadcast("eventTypesLoaded");
        }, 1000);
    });
    $scope.loggedIn = false;
    $scope.username = "";
    $scope.password = "";

    $scope.errorMessage = "";

    $scope.sessionChecked = false;

    $http.get("session.json").
        success(function(data) {
           if(data.loggedIn == true) {
               $scope.loggedIn = true;
               $scope.username = data.username;
           } else {
               $scope.loggedIn = false;
           }
           $scope.sessionChecked = true;
        });

    $scope.login = function() {
        $scope.errorMessage = "";
        if($scope.loggedIn == true)
            return;
        $http.post("login.json", { username: $scope.username, password: $scope.password }).
            success(function(data) {
                if(data.loggedIn == true) {
                    $scope.loggedIn = true;
                    $scope.username = data.username;
                    $scope.password = "";
                } else {
                    $scope.errorMessage = data.errorMessage;
                    $scope.loggedIn = false;
                }
            }).
            error(function(data, status, headers, config) {
                alert("error " + status + ", " + headers);
            });
    };
}]);
angular.module("ptOS").controller("EventsController", ["$scope", "$interval", "Event",
    function($scope, $interval, Event) {

        $scope.filters = "";
        $scope.page = 1;

        $scope.events = [];

        var firstLoad = true;
        var queryParams = {};
        var compileFilter = function() {
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
                    if($scope.eventTypes.indexOf(e.type) > -1) {
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


                $scope.$digest();
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

angular.module("ptOS").controller("PlayersController", ["$scope", "$interval", "Event", "Player", "Server",
    function($scope, $interval, Event, Player, Server) {
        $scope.filters = "";
        $scope.page = 1;

        $scope.players = [];

        var queryParams = {};
        var compileFilter = function() {
            $scope.filters = $scope.filters.trim();
            if(":" in $scope.filters) {
                var parts = $scope.filters.split(":");
                queryParams[parts[0]] = parts[1];
            }
        }

        $scope.filter = function() {
            compileFilter();
            $scope.players = Player.query(queryParams);
        }
        $scope.players = Player.query(queryParams);
    }]);

angular.module("ptOS").controller("PlayerController", ["$scope", "$routeParams", "Player", "Event", "$interval",
    function($scope, $routeParams, Player, Event, $interval) {
        $scope.events = [];

        var playerId = $routeParams.playerId;
        $scope.player = Player.get({ id: playerId }, function() {
            loadEvents();
        });
        // Player event stream
        var firstLoad = true;
        var eventQueryParams = { player: playerId, noPlayer: true };
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