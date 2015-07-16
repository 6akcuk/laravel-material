app.controller('UsersCtrl', [
  '$mdDialog',
  '$users',
  '$scope',
  '$translate',
  'Notification',
  function($mdDialog, $users, $scope, $translate, Notification) {
    'use strict';

    var bookmark;

    $scope.selected = [];

    $scope.filter = {
      options: {
        debounce: 500
      }
    };

    $scope.query = {
      filter: '',
      order: '-created_at',
      limit: '10',
      page: 1
    };

    function success(users) {
      $scope.users = users;
    }

    // Listeners
    $scope.$on('user:changed', function() {
      $scope.onChange();
    });

    $scope.onChange = function() {
      return $users.users.get($scope.query, success).$promise;
    };

    // Add new user
    $scope.addUser = function() {
      $mdDialog.show({
        clickOutsideToClose: true,
        controller: addUserController,
        controllerAs: 'ctrl',
        templateUrl: 'addDialog.html'
      });
    };

    function addUserController($scope, $rootScope, $document) {
      $scope.cancel = $mdDialog.cancel;
      $scope.selectedItem = null;
      $scope.searchText = null;

      this.filterRoles = function(query) {
        var results = query ? $scope.roles.filter(createFilterFor(query)) : $scope.roles;
        return results;
      };

      function createFilterFor(query) {
        var lowercaseQuery = angular.lowercase(query);
        return function filterFn(role) {
          return (angular.lowercase(role.name).indexOf(lowercaseQuery) === 0);
        };
      }

      function success(user) {
        $scope.busy = false;
        $mdDialog.hide();
        $rootScope.$broadcast('user:changed');
      }

      function failure(response) {
        $scope.busy = false;

        $translate('ERROR_MESSAGE').then(function (msg) {
          Notification.error(msg);
        });

        angular.forEach(response.data, function(errors, field) {
          $scope.form[field].$error.remote = true;
          $scope.form[field].$remote = errors;
          $scope.form[field].$invalid = true;
        });
      }

      this.add = function() {
        $scope.busy = true;
        $scope.form.$setSubmitted();

        if ($scope.form.$valid) {
          $users.users.save($scope.user, success, failure);
        }
      };
    }

    // Edit user
    $scope.edit = function(user) {
      $mdDialog.show({
        clickOutsideToClose: true,
        controller: editUserController,
        controllerAs: 'ctrl',
        templateUrl: 'editDialog.html',
        locals: {user: user}
      })
    };

    function editUserController($scope, $rootScope, user) {
      $scope.loading = true;

      this.cancel = $mdDialog.cancel;

      function success(user) {
        $scope.busy = false;
        $mdDialog.hide();

        $translate('UPDATE_SUCCESS_MESSAGE').then(function (msg) {
          Notification.success(msg);
        });

        $rootScope.$broadcast('user:changed');
      }

      function failure(response) {
        $scope.busy = false;

        $translate('ERROR_MESSAGE').then(function (msg) {
          Notification.error(msg);
        });

        angular.forEach(response.data, function(errors, field) {
          $scope.form[field].$error.remote = true;
          $scope.form[field].$remote = errors;
          $scope.form[field].$invalid = true;
        });
      }

      this.edit = function() {
        $scope.busy = true;
        $scope.form.$setSubmitted();

        if ($scope.form.$valid) {
          $users.users.update({id: $scope.user.id}, $scope.user, success, failure);
        }
      };

      $users.users.get({id: user.id}, function(data) {
        $scope.loading = false;

        $scope.user = data;
      }, function() {
        $translate('ERROR_MESSAGE').then(function (msg) {
          Notification.error(msg);
        });

        $mdDialog.hide();
      });
    }

    // Delete selected users
    $scope.delete = function() {
      $mdDialog.show({
        controller: deleteUserController,
        controllerAs: 'ctrl',
        templateUrl: 'deleteDialog.html',
        locals: { users: $scope.selected }
      });
    };

    function deleteUserController($scope, $rootScope, $users, $q, users) {
      this.cancel = $mdDialog.cancel;

      var length = users.length;

      function deleteUser(user, index) {
        var deferred = $users.users.remove({id: user.id});

        deferred.$promise.then(function() {
          users.splice(index, 1);

          if (index == length - 1) {
            $scope.busy = false;
            $rootScope.$broadcast('user:changed');

            $mdDialog.hide();

            $translate('DELETE_SUCCESS_MESSAGE').then(function (msg) {
              Notification.success(msg);
            });
          }
        });

        return deferred.$promise;
      }

      function onComplete() {}

      this.delete = function() {
        $scope.busy = true;
        $q.all(users.forEach(deleteUser)).then(onComplete);
      };
    }


    $scope.updateActive = function(user) {
      $users.users.update({id: user.id}, user, function() {
        $translate('UPDATE_SUCCESS_MESSAGE').then(function (msg) {
          Notification.success(msg);
        });
      });
    };


    $scope.removeFilter = function () {
      $scope.filter.show = false;
      $scope.query.filter = '';

      if($scope.filter.form.$dirty) {
        $scope.filter.form.$setPristine();
      }
    };

    $scope.$watch('query.filter', function (newValue, oldValue) {
      if(!oldValue) {
        bookmark = $scope.query.page;
      }

      if(newValue !== oldValue) {
        $scope.query.page = 1;
      }

      if(!newValue) {
        $scope.query.page = bookmark;
      }

      $scope.deferred = $scope.onChange();
    });
  }
]);