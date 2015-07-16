app.factory('$users', ['$resource', function($resource) {
  'use strict';

  return {
    users: $resource('http://api.jawic.app:8000/v1/users/:id', null, {
      'update': { method: 'PUT' }
    }),
    roles: $resource('http://api.jawic.app:8000/v1/roles/:id', null, {
      'update': { method: 'PUT' }
    })
  };
}]);