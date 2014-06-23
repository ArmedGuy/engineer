var EngineerControllers = angular.module("EngineerControllers", [
    "EngineerServices"
]);

EngineerControllers.controller("IndexController", ["$scope", "$http", function($scope, $http) {

}]);


EngineerControllers.controller("SessionController", ["$scope", "$http", function($scope, $http) {
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

EngineerControllers.controller("PenaltiesController", ["$scope", "Penalty", "Server",
    function($scope, Penalty, Server) {

    $scope.banTypeFilter = 'kicks';
    $scope.gameFilter = null;
    $scope.serverFilter = null;
    $scope.textFilter = "";

    $scope.page = 1;

    $scope.filters = "";
    $scope.typeFilter = function(by) {
        $scope.banTypeFilter = by;
        $scope.applyFilter();
    };
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
            q.server = $scope.serverFilter.server_ident;
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

        q.type = $scope.banTypeFilter;
        q.page = $scope.page;

        queryFilter = q;
    }
    $scope.applyFilter = function() {
        compileFilter();
        $scope.penalties = Penalty.query(queryFilter);
    };
    $scope.servers = Server.query();
    $scope.games = ["bf2", "cod4", "mw2"]; // TODO: compile game list from server list

    $scope.penalties = Penalty.query({ type : $scope.banTypeFilter });
}]);


EngineerControllers.controller("PenaltyController", ["$scope", "$routeParams", "Penalty", "Server", "Admin",
    function($scope, $routeParams, Penalty, Server, Admin) {
        var id = $routeParams.id;

        $scope.penalty = Penalty.get({id: id});
    }]);