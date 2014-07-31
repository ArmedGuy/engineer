(function() {
    "use strict";

    angular.module("ptOS").controller("servers.info", ["$scope", "$http", "$routeParams", "$window", "Server",
        function($scope, $http, $routeParams, $window, Server) {
            var options = {
                center: new google.maps.LatLng(0, 0),
                zoom: 2
                },
                id = $routeParams.serverId;

            $scope.server = Server.get({id: id});
            var mapContainer = $window.document.getElementById("mapCanvas");
            var map = new google.maps.Map(mapContainer, options);

            $scope.distLoaded = false;
            $http.get("servers/" + id + "/distribution.json")
                .success(function(data) {
                    $scope.distLoaded = true;
                    for(var i in data) {
                        var coords = {lat: data[i].location.latitude, lng: data[i].location.longitude};
                        var m = new google.maps.Marker({
                            position: coords,
                            map: map,
                            title: data[i].ip_address
                        });
                    }
                });
        }]);
})();
