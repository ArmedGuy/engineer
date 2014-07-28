(function() {
    "use strict";

    angular.module("ptOS").config(['$routeProvider',
        function($routeProvider) {
            var tmpl = "app/public/js/";
            $routeProvider.
                when('/', {
                    templateUrl: tmpl + 'dashboard/index.html',
                    controller: 'dashboard'
                }).
                when('/events', {
                    templateUrl: tmpl + 'events/list.html',
                    controller: 'events.list'
                }).
                when('/players/:playerId', {
                    templateUrl: tmpl + 'players/info.html',
                    controller: 'players.info'
                }).
                when('/players', {
                    templateUrl: tmpl + 'players/list.html',
                    controller: 'players.list'
                }).
                when('/admins', {
                    templateUrl: tmpl + 'admins/list.html',
                    controller: 'admins.list'
                }).
                otherwise({
                    redirectTo: '/'
                });

        }]);
})();