var app = window.app = angular.module('jawicApp', [
  'ngMaterial',
  'ngMessages',
  'restangular',
  'ngRoute',
  'ngResource',
  'angular-ladda',
  'infinite-scroll',
  'ui-notification',
  'pascalprecht.translate',
  'md.data.table'
], function($interpolateProvider) {
  $interpolateProvider.startSymbol('<%');
  $interpolateProvider.endSymbol('%>');
});

// Translate
app.config(['$translateProvider', '$translatePartialLoaderProvider', function($translateProvider, $translatePartialLoaderProvider) {
  $translatePartialLoaderProvider.addPart('common');
  $translateProvider.useLoader('$translatePartialLoader', {
    urlTemplate: '/js/i18n/{part}/{lang}.json'
  });
  $translateProvider.preferredLanguage('en');
}]);

app.run(function ($rootScope, $translate) {
  $rootScope.$on('$translatePartialLoaderStructureChanged', function () {
    $translate.refresh();
  });
});

// Material theming
app.config(function($mdThemingProvider) {
  $mdThemingProvider.theme('default')
    .primaryPalette('teal');
});

// Ladda
app.config(function (laddaProvider) {
  laddaProvider.setOption({
    style: 'zoom-in'
  });
});

// Notification
app.config(function(NotificationProvider) {
  NotificationProvider.setOptions({
    positionX: 'left',
    positionY: 'bottom'
  });
});