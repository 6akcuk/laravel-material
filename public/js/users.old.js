app.config(function($routeProvider, RestangularProvider) {
  RestangularProvider.setBaseUrl('http://api.jawic.app:8000/v1');

  RestangularProvider.addResponseInterceptor(function(data, operation, what, url, response, deferred) {
    var extractedData;

    if (operation == 'getList') {
      extractedData = data.data || [];
      if (!extractedData[0]) extractedData[0] = {};

      extractedData[0].last_page = data.last_page;
      extractedData[0].current_page = data.current_page;
      extractedData[0].per_page = data.per_page;
      extractedData[0].total = data.total;
    } else {
      extractedData = data;
    }

    return extractedData;
  });
});

app.factory('Users', ['Restangular', function(Restangular) {
  return angular.extend({}, Restangular.service('users'), {

  });
}]);

app.controller('UsersCtrl', [
  '$scope',
  '$mdDialog',
  '$translate',
  'Restangular',
  'Notification',
  'Users',
  function($scope, $mdDialog, $translate, Restangular, Notification, Users) {
    $scope.loading = true;
    $scope.ladda = {};
    $scope.page = 1;
    $scope.noMore = false;
    $scope.users = [];

    function listHandler(users) {
      for (var i = 0; i < users.length; i++) {
        $scope.users.push(users[i]);
      }

      if (users[0].last_page == users[0].current_page) $scope.noMore = true;

      $scope.loading = false;
      $scope.page += 1;
      $scope.total = users[0].total;
    }

    Users.getList().then(listHandler);

    // Events
    $scope.$on('users:userAdded', function(ev, user) {
      $scope.users.unshift(user);
      $scope.total++;
    });

    $scope.$on('users:userUpdated', function(ev, user) {
      for (var i in $scope.users) {
        if ($scope.users[i].id == user.id) $scope.users[i] = user;
      }
    });

    $scope.$watch('search', function() {
      if (typeof $scope.search === 'undefined') return;

      $scope.loading = true;

      Users.getList({q: $scope.search}).then(function(users) {
        $scope.users = users;

        if (users[0].last_page == users[0].current_page) $scope.noMore = true;
        else $scope.noMore = false;

        $scope.loading = false;
        $scope.page = 1;
        $scope.total = users[0].total;
      });
    });

    // Handlers
    $scope.new = function() {
      $mdDialog.show({
        controller: newController,
        templateUrl: 'newDialog.html'
      });
    };

    function newController($scope, $rootScope, $mdDialog) {
      $scope.submitted = false;
      $scope.errors = {};

      $scope.close = function() {
        $mdDialog.hide();
      };

      $scope.add = function() {
        if ($scope.form.$valid) {
          $scope.busy = true;

          Users.post($scope.user).then(function () {
            $scope.busy = false;
            $mdDialog.hide();

            $rootScope.$broadcast('users:userAdded', $scope.user);
          }, function (response) {
            $scope.busy = false;
            $scope.form.$submitted = true;

            $translate('ERROR_MESSAGE').then(function (msg) {
              Notification.error(msg);
            });

            angular.forEach(response.data, function(errors, field) {
              $scope.form[field].$errors = errors;
              $scope.form[field].$invalid = true;
            });
          });
        } else {
          $scope.form.$submitted = true;
        }
      };
    }

    $scope.edit = function(user) {
      $scope.ladda[user.id] = true;

      $mdDialog.show({
        controller: editController,
        templateUrl: 'editDialog.html',
        resolve: {
          user: function() {
            var promise = Users.one(user.id).get();
            promise.then(function() {
              $scope.ladda[user.id] = false;
            });

            return promise;
          }
        }
      });
    };

    function editController($scope, $rootScope, $mdDialog, user) {
      var original = user;
      $scope.user = Restangular.copy(user);

      $scope.close = function() {
        $mdDialog.hide();
      };

      $scope.save = function() {
        if ($scope.form.$valid) {
          $scope.busy = true;

          $scope.user.save().then(function () {
            $scope.busy = false;
            $mdDialog.hide();

            $rootScope.$broadcast('users:userUpdated', $scope.user);
          }, function () {
            $scope.busy = false;
            $scope.form.$submitted = true;

            $translate('ERROR_MESSAGE').then(function (msg) {
              Notification.error(msg);
            });

            angular.forEach(response.data, function (errors, field) {
              $scope.form[field].$errors = errors;
              $scope.form[field].$invalid = true;
            });
          });
        } else {
          $scope.form.$submitted = true;
        }
      };
    }

    $scope.delete = function(user) {
      var confirm = $mdDialog.confirm()
        .title('Confirm user delete')
        .content('Are you sure to delete this user?')
        .ok('Delete')
        .cancel('Cancel');

      $mdDialog.show(confirm).then(function() {
        user.remove().then(function() {
          $scope.users = _.without($scope.users, user);
        });
      });
    };

    $scope.more = function() {
      if ($scope.loading) return;
      $scope.loading = true;

      Users.getList({q: $scope.search, page: $scope.page}).then(listHandler);
    };
  }
]);