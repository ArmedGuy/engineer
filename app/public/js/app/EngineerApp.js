var EngineerApp = angular.module("EngineerApp", [
    "ngRoute",
    "EngineerControllers"
]);

EngineerApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'app/public/partials/index.html',
                controller: 'IndexController'
            }).
            when('/penalties', {
                templateUrl: 'app/public/partials/penalties-list.html',
                controller: 'PenaltiesController'
            }).
            when("/penalties/:id", {
                templateUrl: 'app/public/partials/penalty-info.html',
                controller: 'PenaltyController'
            }).
            when('/servers', {
                templateUrl: 'app/public/partials/servers-list.html',
                controller: 'ServersController'
            }).
            when('/admins', {
                templateUrl: 'app/public/partials/admins-list.html',
                controller: 'AdminsController'
            }).
            otherwise({
                redirectTo: '/'
            });

}]);