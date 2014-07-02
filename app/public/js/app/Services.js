var ptOSServices = angular.module("ptOSServices", ["ngResource"]);

ptOSServices.factory("Penalty", ["$resource", function($resource) {
    return $resource("penalties/:id.json");
}]);

ptOSServices.factory("Admin", ["$resource", function($resource) {
    return $resource("admins/:id.json");
}]);

ptOSServices.factory("Server", ["$resource", function($resource) {
    return $resource("servers/:id.json");
}]);