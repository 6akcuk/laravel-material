<!DOCTYPE html>
<html lang="en" ng-app="jawicApp">
  <head>
    <meta charset="utf-8">

    <title>@section('title') Jawic @show</title>

    <base href="/">

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.flatly.css">
    <link rel="stylesheet" href="bower_components/angular-material/angular-material.css">
    <link rel="stylesheet" href="bower_components/angular-material-data-table/dist/md-data-table.css">
    <link rel="stylesheet" href="bower_components/ladda/dist/ladda-themeless.min.css">
    <link rel="stylesheet" href="bower_components/angular-ui-notification/dist/angular-ui-notification.min.css">

    <!-- Module -->
    <link rel="stylesheet" href="css/common.css">
    @yield('css')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    @include('partials.nav')

    @yield('content')

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Angular Material Dependencies -->
    <script src="bower_components/angular/angular.js"></script>
    <script src="bower_components/angular-resource/angular-resource.js"></script>
    <script src="bower_components/angular-animate/angular-animate.js"></script>
    <script src="bower_components/angular-aria/angular-aria.js"></script>
    <script src="bower_components/angular-messages/angular-messages.js"></script>
    <script src="bower_components/angular-material/angular-material.js"></script>

    <script src="bower_components/angular-route/angular-route.js"></script>

    <!-- Data Table -->
    <script src="bower_components/angular-material-data-table/dist/md-data-table.js"></script>

    <!-- Ladda -->
    <script src="bower_components/ladda/dist/spin.min.js"></script>
    <script src="bower_components/ladda/dist/ladda.min.js"></script>
    <script src="bower_components/angular-ladda/dist/angular-ladda.min.js"></script>

    <!-- Restangular -->
    <script src="bower_components/lodash/lodash.js"></script>
    <script src="bower_components/restangular/src/restangular.js"></script>

    <!-- Notification -->
    <script src="bower_components/angular-ui-notification/dist/angular-ui-notification.min.js"></script>

    <!-- Translate -->
    <script src="bower_components/angular-translate/angular-translate.js"></script>
    <script src="bower_components/angular-translate-loader-partial/angular-translate-loader-partial.js"></script>

    <script src="components/ng-infinite-scroll.js"></script>

    <script src="js/app.js"></script>
    <script src="js/resources.js"></script>
    @yield('javascript')
  </body>
</html>