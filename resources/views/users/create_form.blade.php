<md-input-container>
  <label>{{ trans('users.name') }}</label>
  <input type="text" name="name" ng-model="user.name" required>
  <div ng-messages="form.name.$error">
    <div ng-message="required">Name is required.</div>
    <div ng-repeat="error in form.name.$errors"><% error %></div>
  </div>
</md-input-container>

<!-- Email Form Input -->
<md-input-container>
  <label>{{ trans('users.email') }}</label>
  <input type="email" name="email" ng-model="user.email" required>
  <div ng-messages="form.email.$error" ng-show="form.email.$dirty">
    <div ng-message="required">Email is required.</div>
    <div ng-message="email">This is not a valid email.</div>
    <div ng-message="remote" ng-repeat="error in form.email.$remote"><% error %></div>
  </div>
</md-input-container>

<!-- Password Form Input -->
<md-input-container>
  <label>{{ trans('users.password') }}</label>
  <input type="password" name="password" ng-model="user.password" required>
  <div ng-messages="form.password.$error" ng-show="form.password.$dirty">
    <div ng-message="required">Password is required.</div>
    <div ng-repeat="error in form.password.$errors"><% error %></div>
  </div>
</md-input-container>

<md-checkbox ng-model="user.is_active" ng-true-value="1" ng-false-value="0">
  {{ trans('users.is_active') }}
</md-checkbox>