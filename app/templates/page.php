<!DOCTYPE html>
<html lang="en" ng-app="ptOS">
    <head>
        <meta charset="utf-8" />
        <link href="app/public/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
        <link href="app/public/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
        <link href="app/public/css/angular-csp.css" type="text/css" rel="stylesheet" />
        <link href="app/public/css/style.css" type="text/css" rel="stylesheet" />

        <?php Scripts::render("bootstrap"); ?>
        <?php Scripts::render("app"); ?>
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlAFaClq2z5dfx65atxgn185orpm2Uxe4">
        </script>


      <title>ptOS</title>
    </head>
    <body ng-controller="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#/">ptOS</a>
                </div>
                <ul class="nav navbar-nav ng-cloak" ng-if="loggedIn">
                    <li><a href="#/events/">Event Stream</a></li>
                    <li><a href="#/players/">Players</a></li>
                    <li ng-if="session.type == '2'"><a href="#/admins/">Admins</a></li>
                </ul>
                <form class="navbar-form navbar-right" ng-submit="login()" ng-show="!loggedIn && sessionChecked">
                    <div class="form-group">
                        <input ng-model="username" type="text" class="form-control" placeholder="Username" />
                        <input ng-model="password" type="password" class="form-control" placeholder="Password" />
                    </div>
                   <input type="submit" class="btn btn-default" value="Sign In" />
                </form>
                <p class="navbar-text navbar-right ng-cloak" ng-if="loggedIn">Logged in as {{ session.username }} &nbsp; <a href="/logout" class="navbar-link">Log Out</a></p>
                <p class="navbar-text navbar-right ng-cloak" ng-if="errorMessage.length!=0">{{ errorMessage }}</p>
            </div>
        </nav>
        <div ng-view></div>
    </body>
</html>
