var ptOSApp = angular.module("ptOS", [
    "ngRoute",
    "ngResource"
]);

angular.module("ptOS").filter("timeAgo", function() {
    return function(dateString) {
        return moment(dateString).fromNow();
    }
});

angular.module("ptOS").config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'app/public/partials/index.html',
                controller: 'IndexController'
            }).
            when('/events', {
                templateUrl: 'app/public/partials/events-list.html',
                controller: 'EventsController'
            }).
            when('/players/:playerId', {
                templateUrl: 'app/public/partials/player-info.html',
                controller: 'PlayerController'
            }).
            when('/players', {
                templateUrl: 'app/public/partials/players-list.html',
                controller: 'PlayersController'
            }).
            otherwise({
                redirectTo: '/'
            });

}]);