var ptOSApp = angular.module("ptOSApp", [
    "ngRoute",
    "ptOSControllers"
]);

ptOSApp.config(['$routeProvider',
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
            when('/players', {
                templateUrl: 'app/public/partials/players-list.html',
                controller: 'PlayersController'
            }).
            otherwise({
                redirectTo: '/'
            });

}]);