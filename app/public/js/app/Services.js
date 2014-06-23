var EngineerServices = angular.module("EngineerServices", ["ngResource"]);

EngineerServices.factory("Penalty", ["$resource", function($resource) {
    return $resource("penalties/:id.json");
}]);

EngineerServices.factory("Admin", ["$resource", function($resource) {
    return $resource("admins/:id.json");
}]);

EngineerServices.factory("Server", ["$resource", function($resource) {
    return $resource("servers/:id.json");
}]);