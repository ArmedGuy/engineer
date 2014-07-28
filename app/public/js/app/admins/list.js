(function() {
    "use strict";
    angular.module("ptOS").controller("admins.list", ["$scope", "Admin", function($scope, Admin) {

        $scope.admins = Admin.query();
        $scope.newUsername = "";
        $scope.newPassword = "";
        $scope.newType = 1;
        $scope.create = function() {
            var username = $scope.newUsername;
            var password = $scope.newPassword;
            var type = $scope.newType;

            Admin.save({username: username, password: password, type: type});
            $scope.admins.push({ username: username, type: type});

        }

        $scope.tryDelete = function(admin) {
            if(confirm("Are you sure you want to delete user " + admin.username + "?")) {
                Admin.delete({id: admin.id});
                var i = $scope.admins.indexOf(admin);
                $scope.admins.splice(i, 1);
            }
        }
    }]);
})();