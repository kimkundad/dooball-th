<header class="main-header">
	<!-- Logo -->
	<a href="/" class="logo" title="หน้าเว็บ All2dooball">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">DB</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">Dooball</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li>
            <a href="{{ URL::to('admin/logout') }}" class="btn">
              ออกจากระบบ&nbsp;<i class="fa fa-sign-out"></i>
            </a>
          </li>
        </ul>
      </div>
    </nav>
</header>
<input type="hidden" id="base_url" value="{{ URL::to('/') }}" />