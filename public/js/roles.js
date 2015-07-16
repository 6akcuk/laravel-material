app.controller('RolesCtrl', [
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

    function success(roles) {
      $scope.roles = roles;
    }

    // Listeners
    $scope.$on('role:changed', function() {
      $scope.onChange();
    });

    $scope.onChange = function() {
      return $users.roles.get($scope.query, success).$promise;
    };

    // Add new role
    $scope.addRole = function() {
      $mdDialog.show({
        clickOutsideToClose: true,
        controller: addRoleController,
        controllerAs: 'ctrl',
        templateUrl: 'addDialog.html'
      });
    };

    function addRoleController($scope, $rootScope) {
      this.cancel = $mdDialog.cancel;

      function success(role) {
        $scope.busy = false;
        $mdDialog.hide();
        $rootScope.$broadcast('role:changed');
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
          $users.roles.save($scope.role, success, failure);
        }
      };
    }

    // Edit role
    $scope.edit = function(role) {
      $mdDialog.show({
        clickOutsideToClose: true,
        controller: editRoleController,
        controllerAs: 'ctrl',
        templateUrl: 'editDialog.html',
        locals: {role: role}
      })
    };

    function editRoleController($scope, $rootScope, role) {
      $scope.loading = true;

      this.cancel = $mdDialog.cancel;

      function success(user) {
        $scope.busy = false;
        $mdDialog.hide();

        $translate('UPDATE_SUCCESS_MESSAGE').then(function (msg) {
          Notification.success(msg);
        });

        $rootScope.$broadcast('role:changed');
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
          $users.roles.update({id: $scope.role.id}, $scope.role, success, failure);
        }
      };

      $users.roles.get({id: role.id}, function(data) {
        $scope.loading = false;

        $scope.role = data;
      }, function() {
        $translate('ERROR_MESSAGE').then(function (msg) {
          Notification.error(msg);
        });

        $mdDialog.hide();
      });
    }

    // Delete selected roles
    $scope.delete = function() {
      $mdDialog.show({
        controller: deleteRoleController,
        controllerAs: 'ctrl',
        templateUrl: 'deleteDialog.html',
        locals: { roles: $scope.selected }
      });
    };

    function deleteRoleController($scope, $rootScope, $users, $q, roles) {
      this.cancel = $mdDialog.cancel;

      var length = roles.length;

      function deleteRole(role, index) {
        var deferred = $users.roles.remove({id: role.id});

        deferred.$promise.then(function() {
          roles.splice(index, 1);

          if (index == length - 1) {
            $scope.busy = false;
            $rootScope.$broadcast('role:changed');

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
        $q.all(roles.forEach(deleteRole)).then(onComplete);
      };
    }

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