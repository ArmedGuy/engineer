var ptOSServices = angular.module("ptOSServices", ["ngResource"]);

ptOSServices.factory("Event", ["$resource", function($resource) {
    return $resource("events/:id.json");
}]);
ptOSServices.factory("Player", ["$resource", function($resource) {
    return $resource("players/:id.json");
}]);

ptOSServices.factory("Admin", ["$resource", function($resource) {
    return $resource("admins/:id.json");
}]);

ptOSServices.factory("Server", ["$resource", function($resource) {
    return $resource("servers/:id.json");
}]);