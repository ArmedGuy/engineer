

angular.module("ptOS").factory("Event", ["$resource", function($resource) {
    return $resource("events/:id.json");
}]);
angular.module("ptOS").factory("Player", ["$resource", function($resource) {
    return $resource("players/:id.json");
}]);

angular.module("ptOS").factory("Admin", ["$resource", function($resource) {
    return $resource("admins/:id.json");
}]);

angular.module("ptOS").factory("Server", ["$resource", function($resource) {
    return $resource("servers/:id.json");
}]);