app.factory('$users', ['$resource', function($resource) {
  'use strict';

  return {
    users: $resource('http://api.material.cookapp.club/v1/users/:id', null, {
      'update': { method: 'PUT' }
    }),
    roles: $resource('http://api.material.cookapp.club/v1/roles/:id', null, {
      'update': { method: 'PUT' }
    })
  };
}]);