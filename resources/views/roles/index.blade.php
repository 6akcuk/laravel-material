@extends('app')

@section('title') Roles - @parent @stop

@section('content')
  <div class="container" ng-controller="RolesCtrl">
    <div class="row">
      <md-card>
        <md-data-table-toolbar ng-hide="selected.length || filter.show">
          <h2 class="md-title">
            {{ trans('roles.roles') }}
            <span class="badge"><% roles.total %></span>
          </h2>
          <div flex></div>
          <button class="md-icon-button md-button" ng-click="filter.show = true">
            <i class="material-icons md-dark">filter_list</i>
          </button>
          <button class="md-icon-button md-button" ng-click="addRole()">
            <i class="material-icons md-dark">add_circle_outline</i>
          </button>
        </md-data-table-toolbar>
        <md-data-table-toolbar ng-show="filter.show && !selected.length">
          <form flex name="filter.form">
            <md-input-container md-no-float>
              <md-icon md-svg-src="bower_components/material-design-icons/action/svg/design/ic_search_24px.svg"></md-icon>

              <input type="text" ng-model="query.filter" ng-model-options="filter.options" placeholder="search">
            </md-input-container>
          </form>
          <button class="md-icon-button md-button" ng-click="removeFilter()">
            <i class="material-icons md-dark">close</i>
          </button>
        </md-data-table-toolbar>
        <md-data-table-toolbar class="alert-info" ng-show="selected.length" layout-align="space-between">
          <div><% selected.length %> selected</div>
          <button class="md-icon-button md-button" ng-click="delete($event)">
            <i class="material-icons">delete</i>
          </button>
        </md-data-table-toolbar>

        <md-data-table-container>
          <table md-data-table md-row-select="selected" md-progress="deferred">
            <thead md-order="query.order" md-trigger="onChange">
              <tr>
                <th order-by="name">{{ trans('roles.name') }}</th>
                <th order-by="created_at">{{ trans('roles.created_at') }}</th>
              </tr>
            </thead>
            <tbody ng-show="roles.data.length">
              <tr ng-repeat="role in roles.data" ng-click="edit(role)">
                <td><% role.name %></td>
                <td><% role.created_at | date %></td>
              </tr>
            </tbody>
            <tfoot ng-hide="roles.data.length">
              <tr>
                <td>{{ trans('roles.empty') }}</td>
              </tr>
            </tfoot>
          </table>
        </md-data-table-container>

        <md-data-table-toolbar layout-align="end">
          <md-data-table-pagination md-limit="query.limit" md-page="query.page" md-total="<% roles.total %>" md-trigger="onChange"></md-data-table-pagination>
        </md-data-table-toolbar>
      </md-card>
    </div>
  </div>

  <script type="text/ng-template" id="addDialog.html">
    <md-dialog aria-label="Add role" flex="20">
      <md-dialog-content>
        <h2 class="md-title">New Role</h2>
        <form name="form" class="form-horizontal">
          @include('roles.form')
        </form>
      </md-dialog-content>
      <div class="md-actions">
        <button class="md-button md-primary" ng-click="ctrl.add()" ladda="busy" data-spinner-color="#149c82" ng-disabled="form.$invalid" >
          Add
        </button>
        <md-button ng-click="ctrl.cancel()" class="md-primary">
          Cancel
        </md-button>
      </div>
    </md-dialog>
  </script>

  <script type="text/ng-template" id="deleteDialog.html">
    <md-dialog aria-label="Delete role" flex="20">
      <md-dialog-content>
        <h2 class="md-title">Delete Roles</h2>
        {{ trans('roles.delete_prompt') }}
      </md-dialog-content>
      <div class="md-actions">
        <button ng-click="ctrl.delete()" ladda="busy" data-spinner-color="#149c82" class="md-button md-primary">
          Delete
        </button>
        <md-button ng-click="ctrl.cancel()" class="md-primary">
          Cancel
        </md-button>
      </div>
    </md-dialog>
  </script>

  <script type="text/ng-template" id="editDialog.html">
    <md-dialog aria-label="Edit role" flex="20">
      <md-dialog-content layout="row" layout-align="center center" ng-show="loading">
        <md-progress-circular md-mode="indeterminate"></md-progress-circular>
      </md-dialog-content>
      <md-dialog-content ng-hide="loading">
        <h2 class="md-title">Edit Role</h2>
        <form name="form" class="form-horizontal">
          @include('roles.form')
        </form>
      </md-dialog-content>
      <div class="md-actions">
        <button ng-click="ctrl.edit()" ladda="busy" ng-disabled="form.$invalid" data-spinner-color="#149c82" class="md-button md-primary">
          Save
        </button>
        <md-button ng-click="ctrl.cancel()" class="md-primary">
          Cancel
        </md-button>
      </div>
    </md-dialog>
  </script>
@endsection

@section('javascript')
  <script type="text/javascript" src="js/roles.js"></script>
@endsection