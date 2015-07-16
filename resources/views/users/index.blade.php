@extends('app')

@section('title') Users - @parent @stop

@section('content')
  <div class="container" ng-controller="UsersCtrl">
    <div class="row">
      <md-card>
        <md-data-table-toolbar ng-hide="selected.length || filter.show">
          <h2 class="md-title">
            {{ trans('users.users') }}
            <span class="badge"><% users.total %></span>
          </h2>
          <div flex></div>
          <button class="md-icon-button md-button" ng-click="filter.show = true">
            <i class="material-icons md-dark">filter_list</i>
          </button>
          <button class="md-icon-button md-button" ng-click="addUser()">
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
                <th order-by="name">{{ trans('users.name') }}</th>
                <th>{{ trans('users.email') }}</th>
                <th>{{ trans('users.roles') }}</th>
                <th order-by="created_at">{{ trans('users.created_at') }}</th>
                <th>{{ trans('users.is_active') }}</th>
              </tr>
            </thead>
            <tbody ng-show="users.data.length">
              <tr ng-repeat="user in users.data" ng-click="edit(user)">
                <td><% user.name %></td>
                <td><% user.email %></td>
                <td>
                  <span ng-repeat="role in user.roles"><% role %></span>
                </td>
                <td><% user.created_at | date %></td>
                <td ng-click="$event.stopPropagation()"><md-switch ng-model="user.is_active" ng-true-value="1" ng-false-value="0" ng-change="updateActive(user)">Active</md-switch></td>
              </tr>
            </tbody>
            <tfoot ng-hide="users.data.length">
              <tr>
                <td>{{ trans('users.empty') }}</td>
              </tr>
            </tfoot>
          </table>
        </md-data-table-container>

        <md-data-table-toolbar layout-align="end">
          <md-data-table-pagination md-limit="query.limit" md-page="query.page" md-total="<% users.total %>" md-trigger="onChange"></md-data-table-pagination>
        </md-data-table-toolbar>

        <!--<div class="page-header">
          <h3>
            <div class="pull-left">
              {{ trans('users.users') }}
              <span class="badge"><% total %></span>
            </div>

            <div class="pull-left search-container">
              <input ng-model="search" ng-model-options="{ debounce: 250 }" class="form-control input-sm" placeholder="Search users..">
            </div>

            <div class="pull-right">
              <a ng-click="new()" class="btn btn-sm btn-primary">
                <span class="glyphicon glyphicon-plus-sign"></span>
                {{ trans('common.new') }}
              </a>
            </div>
          </h3>
        </div>-->

        <!--<table infinite-scroll="more()" infinite-scroll-disabled="loading || noMore" class="table table-striped table-hover datatable">
          <thead>
          <tr>
            <th>{{ trans('users.name') }}</th>
            <th>{{ trans('users.email') }}</th>
            <th>{{ trans('users.active') }}</th>
            <th>{{ trans('common.actions') }}</th>
          </tr>
          </thead>
          <tbody ng-cloak>
            <tr ng-repeat="user in users">
              <td><% user.name %></td>
              <td><% user.email %></td>
              <td><% user.is_active ? '{{ trans('common.yes') }}' : '{{ trans('common.no') }}' %></td>
              <td>
                <a ladda="ladda[user.id]" ng-click="edit(user)" data-style="zoom-in" class="btn btn-success btn-sm">
                  <span class="glyphicon glyphicon-pencil"></span>
                  {{ trans('common.edit') }}
                </a>
                <a ng-click="delete(user)" class="btn btn-danger btn-sm">
                  <span class="glyphicon glyphicon-trash"></span>
                  {{ trans('common.delete') }}
                </a>
              </td>
            </tr>
          </tbody>
        </table>-->

        <!--<div class="center-block" style="width: 50px">
          <md-progress-circular ng-show="loading" class="" md-mode="indeterminate"></md-progress-circular>
        </div>-->
      </md-card>
    </div>
  </div>

  <script type="text/ng-template" id="addDialog.html">
    <md-dialog aria-label="Add user" flex="20">
      <md-dialog-content layout="row" layout-align="center center" ng-show="loading">
        <md-progress-circular md-mode="indeterminate"></md-progress-circular>
      </md-dialog-content>
      <md-dialog-content ng-hide="loading">
        <h2 class="md-title">New User</h2>
        <form name="form" class="form-horizontal">
          @include('users.create_form')
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
    <md-dialog aria-label="Delete user" flex="20">
      <md-dialog-content>
        <h2 class="md-title">Delete Users</h2>
        {{ trans('users.delete_prompt') }}
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
    <md-dialog aria-label="Edit user" flex="20">
      <md-dialog-content layout="row" layout-align="center center" ng-show="loading">
        <md-progress-circular md-mode="indeterminate"></md-progress-circular>
      </md-dialog-content>
      <md-dialog-content ng-hide="loading">
        <h2 class="md-title">Edit User</h2>
        <form name="form" class="form-horizontal">
          @include('users.form')
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
  <script type="text/javascript" src="js/users.js"></script>
@endsection