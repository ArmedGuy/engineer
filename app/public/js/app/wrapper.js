(function() {
    "use strict";
    angular.module("ptOS").controller("wrapper", ["$scope", "$http", "$route", function($scope, $http, $route) {

        $scope.eventTypes = [];
        $http.get("app/public/event-types.json").success(function(data) {
            $scope.eventTypes = data;
        });
        $scope.loggedIn = false;
        $scope.username = "";
        $scope.password = "";

        $scope.errorMessage = "";

        $scope.sessionChecked = false;

        function checkSession() {
        $http.get("session.json").
            success(function(data) {
                if(data.loggedIn == true) {
                    $scope.loggedIn = true;
                    $scope.session = data;
                } else {
                    $scope.loggedIn = false;
                }
                $scope.sessionChecked = true;
            });
        }
        checkSession();
        $scope.login = function() {
            $scope.errorMessage = "";
            if($scope.loggedIn == true)
                return;
            $http.post("login.json", { username: $scope.username, password: $scope.password }).
                success(function(data) {
                    if(data.loggedIn == true) {
                        $scope.username = data.username;
                        $scope.password = "";
                        checkSession();
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
})();