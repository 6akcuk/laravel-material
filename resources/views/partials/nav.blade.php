<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="/">JawicApp</a>
    </div>

    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        @if (Authority::can('manage', 'App\User'))
        <li class="dropdown {{ Request::is('users') || Request::is('roles') ? 'active' : '' }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Users <span class="caret"></span>
            <ul class="dropdown-menu">
              <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="{{ route('users.index') }}">{{ trans('users.users') }}</a></li>
              <li class="{{ Request::is('roles') ? 'active' : '' }}"><a href="{{ route('roles.index') }}">{{ trans('roles.roles') }}</a></li>
            </ul>
          </a>
        </li>
        @endif
      </ul>
    </div>
  </div>
</nav>