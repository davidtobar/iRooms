<aside class="main-sidebar side" >
  <div class="irooms-l">
    <a href="{{ route('schedule-monthly') }}">
      <span>
        <img src="{{$login->logo}}" alt="">
      </span>
    </a>
  </div>
  <section class="sidebar">
    <ul class="sidebar-menu sidebarp" data-widget="tree">
      <li class="header">MENU</li>
      <li class="{{ request()->is('admin/schedule/*') ? 'treeview active menu-open' : 'treeview' }}">
        <a href="#">
          <i class="mdi mdi-calendar-range"></i></i> <span>Schedule</span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('admin/schedule/monthly-view') ? 'active' : '' }}"><a href="{{ route('schedule-monthly') }}">Monthly View</a></li>
          <li class="{{ request()->is('admin/schedule/weekly-view') ? 'active' : '' }}"><a href="{{ route('schedule-weekly') }}">Weekly View</a></li>
          <li class="{{ request()->is('admin/schedule/daily-view') ? 'active' : '' }}"><a href="{{ route('schedule-daily') }}">Daily View</a></li>          
        </ul>
      </li>
      <li class="{{ request()->is('admin/account/*') ? 'treeview active menu-open' : 'treeview' }}">
        <a href="#">
          <i class="mdi mdi-account"></i>
          <span>Account</span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('admin/account/my-meetings') ? 'active' : '' }}"><a href="{{ route('meetings') }}">My Meetings</a></li>
          <li class="{{ request()->is('admin/account/meetings-approval') ? 'active' : '' }}"><a href="{{ route('meetings-approval') }}">Meetings approval</a></li>
          <li class="{{ request()->is('admin/account/my-account') ? 'active' : '' }}"><a href="{{ route('profile') }}">My Account</a></li>        
        </ul>
      </li>
      <li class="{{ request()->is('admin/manage/*') ? 'treeview active menu-open' : 'treeview' }}">
        <a href="#">
          <i class="fa fa-cog"></i> <span>Manage</span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ request()->is('admin/manage/users') ? 'active' : '' }}"><a href="{{ route('user-list') }}">Users</a></li>
          <li class="{{ request()->is('admin/manage/rooms') ? 'active' : '' }}"><a href="{{ route('room-list') }}">Rooms</a></li>
          <li class="{{ request()->is('admin/manage/resources') ? 'active' : '' }}"><a href="{{ route('resource-list') }}">Resources</a></li>
          <li class="{{ request()->is('admin/manage/settings') ? 'active' : '' }}"><a href="{{ route('settings') }}">Settings</a></li>       
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