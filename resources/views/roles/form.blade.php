<!-- Name Form Input -->
<md-input-container>
  <label>Name</label>
  <input type="text" ng-model="role.name" required>
  <div ng-messages="form.name.$error" ng-show="form.name.$dirty">
    <div ng-message="required">Name is required.</div>
    <div ng-message="remote" ng-repeat="error in form.name.$remote"><% error %></div>
  </div>
</md-input-container>