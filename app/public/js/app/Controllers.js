var EngineerControllers = angular.module("EngineerControllers", [
    "EngineerServices"
]);

EngineerControllers.controller("IndexController", ["$scope", "$http", function($scope, $http) {

}]);


EngineerControllers.controller("SiteController", ["$scope", "$http", function($scope, $http) {

    $scope.penaltyReasons = [];
    $http.get("penalty_reasons.json").success(function(data) {
       $scope.penaltyReasons = data;
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

EngineerControllers.controller("PenaltiesController", ["$scope", "$interval", "Penalty", "Server",
    function($scope, $interval, Penalty, Server) {

        $scope.gameFilter = null;
        $scope.serverFilter = null;
        $scope.textFilter = "";

        $scope.page = 1;

        $scope.filters = "";

        var queryFilter = {};
        var compileFilter = function() {
            var q = {};
            var f = [];
            if($scope.gameFilter != null) {
                q.game = $scope.gameFilter;
                f.push("game: " + $scope.gameFilter);
            }

            if($scope.serverFilter != null) {
                $scope.gameFilter = null;

                q = {};
                q.server = $scope.serverFilter;
                f = [];
                f.push("server: " + $scope.serverFilter.name);
            }
            if($scope.textFilter != "") {
                q.text = $scope.textFilter;
                f.push("text: " + $scope.textFilter);
            }
            if(f.length > 0) {
                $scope.filters = "filter by " + f.join(", ");
            } else {
                $scope.filters = "";
            }

            q.page = $scope.page;
            $scope.filters = f.join(", ");

            queryFilter = q;
        }
        $scope.applyFilter = function() {
            compileFilter();
            $scope.penalties = Penalty.query(queryFilter);
        };
        $scope.servers = Server.query();
        $scope.games = ["bf2", "cod4", "mw2"]; // TODO: compile game list from server list

        $scope.penalties = Penalty.query();

        var updateLoop = $interval(function() {
            compileFilter();

            for(var i in $scope.penalties) {
                $scope.penalties[i].isNew = false;
            }
            if($scope.penalties.length > 0) {
                queryFilter.after = $scope.penalties[0].id;
            } else {
                queryFilter.after = 0;
            }
            Penalty.query(queryFilter, function(newPenalties) {
                newPenalties = newPenalties.reverse(); // we want latest to be last because of unshift
                for(var i = 0; i < newPenalties.length; i++)
                {
                    if($scope.penalties.length == 100) {
                        $scope.penalties.pop();
                        newPenalties[i].isNew = true;
                        $scope.penalties.unshift(newPenalties[i]);
                    } else {
                        newPenalties[i].isNew = true;
                        $scope.penalties.unshift(newPenalties[i]);
                    }
                }
            });
            queryFilter.after = 0;
        }, 10000);

        $scope.$on("$destroy", function() {
           $interval.cancel(updateLoop);
        });
}]);


EngineerControllers.controller("PenaltyController", ["$scope", "$routeParams", "Penalty", "Server", "Admin",
    function($scope, $routeParams, Penalty, Server, Admin) {
        var id = $routeParams.id;
        $scope.loaded = false;
        $scope.penalty = {};
        $scope.penalty = Penalty.get({id: id}, function() {
            $scope.loaded = true;
        });
    }]);