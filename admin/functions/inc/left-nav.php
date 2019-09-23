<?php
if(!$loggedInUser->checkPermission(array(2,12))){
    header("Location: /forum/") and die();

}
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="https://www.habbo.com/habbo-imaging/avatarimage?user=<?php echo $loggedInUser->username; ?>&gesture=sml&headonly=1" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $loggedInUser->display_name; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>User</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/forum/admin/user/view.php"><i class="fa fa-circle-o"></i> View</a></li>
                    <li><a href="/forum/admin/user/infringed.php"><i class="fa fa-circle-o"></i> Infringed</a></li>
                    <li><a href="/forum/admin/user/user-logs.php"><i class="fa fa-circle-o"></i> User Logs</a></li>
                    <li><a href="/forum/admin/user/recent-edits.php"><i class="fa fa-circle-o"></i> Recent Edits</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-edit"></i> <span>Filters</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> Manage</a></li>
                    <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Occurrences</a></li>
                    <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Reports</a></li>
                </ul>
            </li>

            <?php if ($loggedInUser->checkPermission(array(2))){
                ?>
                <li class="treeview">
                    <a href="#">
                        <i class="ion ion-locked"></i> <span>Admin</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/forum/admin/admin/layout.php"><i class="fa fa-circle-o"></i> Layout</a></li>
                        <li><a href="/forum/admin/admin/mod-logs.php"><i class="fa fa-circle-o"></i> Moderator Logs</a></li>
                        <li><a href="/forum/admin/admin/permissions.php"><i class="fa fa-circle-o"></i> Permissions</a></li>
                        <li><a href="/forum/admin/admin/reports.php"><i class="fa fa-circle-o"></i> Reports</a></li>
                    </ul>
                </li>
            <?php
            }?>



            <li class="header">LABELS</li>
            <li><a href="/"><i class="fa fa-circle-o text-red"></i> <span>Portal Home</span></a></li>
            <li><a href="/forum"><i class="fa fa-circle-o text-yellow"></i> <span>Forum Home</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>