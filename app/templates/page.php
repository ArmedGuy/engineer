<!DOCTYPE html>
<html lang="en" ng-app="EngineerApp">
    <head>
        <meta charset="utf-8" />
        <link href="app/public/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
        <link href="app/public/css/angular-csp.css" type="text/css" rel="stylesheet" />
        <link href="app/public/css/style.css" type="text/css" rel="stylesheet" />
        <script src="app/public/js/angular.min.js"></script>
        <script src="app/public/js/angular-route.min.js"></script>
        <script src="app/public/js/angular-resource.min.js"></script>
        <script src="app/public/js/chartjs.min.js"></script>
        <script src="app/public/js/app/EngineerApp.js"></script>
        <script src="app/public/js/app/Controllers.js"></script>
        <script src="app/public/js/app/Services.js"></script>
        <title>Engineer</title>
    </head>
    <body ng-controller="SiteController">
        <nav class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#/">Engineer</a>
                </div>
                <ul class="nav navbar-nav ng-cloak" ng-if="loggedIn">
                    <li><a href="#/penalties/">Penalties</a></li>
                    <li><a href="#/servers/">Servers</a></li>
                    <li><a href="#/admins/">Admins</a></li>
                </ul>
                <form class="navbar-form navbar-right" ng-submit="login()" ng-show="!loggedIn && sessionChecked">
                    <div class="form-group">
                        <input ng-model="username" type="text" class="form-control" placeholder="Username" />
                        <input ng-model="password" type="password" class="form-control" placeholder="Password" />
                    </div>
                   <input type="submit" class="btn btn-default" value="Sign In" />
                </form>
                <p class="navbar-text navbar-right ng-cloak" ng-if="loggedIn">Logged in as {{ username }} &nbsp; <a href="/logout" class="navbar-link">Log Out</a></p>
                <p class="navbar-text navbar-right ng-cloak" ng-if="errorMessage.length!=0">{{ errorMessage }}</p>
            </div>
        </nav>
        <div ng-view></div>
    </body>
</html>
