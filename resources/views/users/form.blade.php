<!-- Name Form Input -->
<md-input-container>
  <label>Name</label>
  <input type="text" ng-model="user.name" required>
  <div ng-messages="form.name.$error" ng-show="form.name.$dirty">
    <div ng-message="required">Name is required.</div>
    <div ng-message="remote" ng-repeat="error in form.name.$remote"><% error %></div>
  </div>
</md-input-container>

<!-- Email Form Input -->
<md-input-container>
  <label>Email</label>
  <input type="email" ng-model="user.email" required>
  <div ng-messages="form.email.$error" ng-show="form.email.$dirty">
    <div ng-message="required">Email is required.</div>
    <div ng-message="email">This is not a valid email.</div>
    <div ng-message="remote" ng-repeat="error in form.email.$remote"><% error %></div>
  </div>
</md-input-container>

<md-checkbox ng-model="user.is_active" ng-true-value="1" ng-false-value="0">
  {{ trans('users.is_active') }}
</md-checkbox>