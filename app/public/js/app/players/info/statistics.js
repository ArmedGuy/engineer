(function() {
    "use strict";

    angular.module("ptOS").controller("players.info.statistics", ["$scope", "$routeParams", "$http", "$window",
        function($scope, $routeParams, $http, $window) {
            function getRandomColor() {
                var letters = '0123456789ABCDEF'.split('');
                var color = '#';
                for (var i = 0; i < 6; i++ ) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            var playerId = $routeParams.playerId;
            $http.get("players/" + playerId + "/stats/types.json")
                .success(function(stats) {
                    var data = [];
                    for(var s in stats) {
                        var stat = stats[s];
                        data.push({
                            value: parseInt(stat.numevents),
                            label: stat.type,
                            color: $scope.eventTypes[stat.type],
                            highlight: $scope.eventTypes[stat.type]
                        });
                    }
                    var ctx = $window.document.getElementById("playerEventTypesChart").getContext("2d");
                    var chart = new Chart(ctx).Doughnut(data, {
                        animateScale: true
                    });
                });
            $http.get("players/" + playerId + "/stats/servers.json")
                .success(function(servers) {
                    var data = [];
                    for(var s in servers) {
                        var server = servers[s];
                        var color = getRandomColor();
                        data.push({
                            label: server.server_name,
                            value: parseInt(server.favweight),
                            color: color,
                            highlight: color
                        });
                    }
                    var ctx = $window.document.getElementById("playerFavoriteServers").getContext("2d");
                    var chart = new Chart(ctx).Pie(data, {
                        animateScale: true
                    });
                });
            $http.get("players/" + playerId + "/stats/toaverage.json")
                .success(function(stats) {
                    var labels = [];
                    var playerValues = [];
                    var averageValues = [];
                    for(var i in stats.player) {
                        labels.push(i);
                        playerValues.push(parseInt(stats.player[i]));
                        averageValues.push(parseInt(stats.average[i]));
                    }
                    var datasets = [
                        {
                            label: "Player",
                            fillColor: "rgba(220,220,220,0.2)",
                            strokeColor: "rgba(220,220,220,1)",
                            pointColor: "rgba(220,220,220,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: playerValues
                        },
                        {
                            label: "Average",
                            fillColor: "rgba(151,187,205,0.2)",
                            strokeColor: "rgba(151,187,205,1)",
                            pointColor: "rgba(151,187,205,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(151,187,205,1)",
                            data: averageValues
                        }
                    ];
                    var ctx = $window.document.getElementById("playerAverage").getContext("2d");
                    var char = new Chart(ctx).Radar({
                        labels: labels,
                        datasets: datasets
                    });
                });

            $http.get("players/" + playerId + "/stats/hours.json")
                .success(function(hours) {
                    var labels = [];
                    for(var i in hours) {
                        labels.push("" + i);
                    }
                    var data = {
                        labels: labels,
                        datasets: [
                            {
                                label: "Hours",
                                fillColor: "rgba(151,187,205,0.5)",
                                strokeColor: "rgba(151,187,205,0.8)",
                                highlightFill: "rgba(151,187,205,0.75)",
                                highlightStroke: "rgba(151,187,205,1)",
                                data: hours
                            }
                        ]
                    }
                    var ctx = $window.document.getElementById("playerActivityDay").getContext("2d");
                    var chart = new Chart(ctx).Bar(data);
                })
        }]);
})();