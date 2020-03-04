<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset("/AdminLTE-2.3.11/dist/img/GuestAvatar128.png") }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Hendarismanto</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">Navigation</li>
            <!-- Optionally, you can add icons to the links -->

            <li class="{{ (request()->is('groups*')) ? 'active' : '' }}"><a href="{{ action('GroupController@index') }}"><i class="fa fa-bitcoin"></i> <span>Group</span></a></li>
            <li class="{{ (request()->is('contacts*')) ? 'active' : '' }}"><a href="{{ action('ContactController@index') }}"><i class="fa fa-book"></i> <span>Contact</span></a></li>

        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>