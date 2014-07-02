var ptOSApp = angular.module("ptOSApp", [
    "ngRoute",
    "ptOSControllers"
]);

ptOSApp.filter('penaltyType', function() {
   return function(input) {
       switch (input.toString()) {
           case "1":
               return "Warning";
           case "2":
               return "Kick";
           case "3":
               return "Tempban";
           case "4":
               return "Ban";
           default:
               return "Unknown";
       }
   }
});
ptOSApp.filter('penaltyDuration', function() {
   return function(input) {
       var m = parseInt(input);
       if(m == 0) {
           return "Permanent";
       }
       if(m == 1) {
           return "1 minute";
       }
       if(m > 59) {
           return (m / 60.0) + " hours";
       } else {
           return m + " minutes";
       }
   }
});

ptOSApp.filter('penaltyReason', function() {
   return function(input, types) {
       for(var i in types) {
           if(types[i].reason_id == input) {
               return types[i].name;
           }
       }
       return "Other";
   }
});

ptOSApp.config(['$routeProvider',
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