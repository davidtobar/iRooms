<aside class="main-sidebar side" >
  <div class="irooms-l">
    <a href="{{ route('user-schedule-monthly') }}">
      <span>
        <img src="{{$login->logo}}" alt="">
      </span>
    </a>
  </div>
  <section class="sidebar">
    <ul class="sidebar-menu sidebarp" data-widget="tree">
      <li class="header">MENU</li>
      <li class="{{ request()->is('user/schedule/*') ? 'treeview active menu-open' : 'treeview' }}">
        <a href="#">
          <i class="mdi mdi-calendar-range"></i></i> <span>Schedule</span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('user/schedule/monthly-view') ? 'active' : '' }}"><a href="{{ route('user-schedule-monthly') }}">Monthly View</a></li>
          <li class="{{ request()->is('user/schedule/weekly-view') ? 'active' : '' }}"><a href="{{ route('user-schedule-weekly') }}">Weekly View</a></li>
          <li class="{{ request()->is('user/schedule/daily-view') ? 'active' : '' }}"><a href="{{ route('user-schedule-daily') }}">Daily View</a></li>          
        </ul>
      </li>
      <li class="{{ request()->is('user/account/*') ? 'treeview active menu-open' : 'treeview' }}">
        <a href="#">
          <i class="mdi mdi-account"></i>
          <span>Account</span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('user/account/my-account') ? 'active' : '' }}"><a href="{{ route('user-profile') }}">My Account</a></li>        
        </ul>
      </li>
    </ul>

    <div class="sidebar-footer" id="sidebar-footer">
      @guest
      <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <div class="footer-left">
          <span>User</span> <i class="mdi mdi-logout"></i>
        </div>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
      </form>
      @else
      <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <div class="footer-left">
          <span>{{ Auth::user()->name }}</span> <i class="mdi mdi-logout"></i>
        </div>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
      </form>
      @endguest
    </div>
  </section>
</aside>