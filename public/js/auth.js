var app = angular.module('jawicApp', [
  'ngMaterial'
], function($interpolateProvider) {
  $interpolateProvider.startSymbol('<%');
  $interpolateProvider.endSymbol('%>');
});

app.controller('AuthCtrl', ['$scope', function($scope) {

}]);