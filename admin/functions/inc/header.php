<?php
/**
 * phpStorm.
 * User: JEFFH14
 * Date: 03/02/2017
 * Time: 8:18 PM
 */

?>

<header class="main-header">
    <!-- Logo -->
    <a href="/forum" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>SS</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Habbo </b>SS Forum</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="https://www.habbo.com/habbo-imaging/avatarimage?user=<?php echo $loggedInUser->username; ?>&direction=3&head_direction=3&gesture=sml&headonly=1" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo $loggedInUser->display_name; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="https://www.habbo.com/habbo-imaging/avatarimage?user=<?php echo $loggedInUser->username; ?>&direction=3&head_direction=3&gesture=sml&headonly=1" class="img-circle" alt="User Image">
                            <p>
                                <?php echo $loggedInUser->display_name; ?>
                                <?php if($loggedInUser->checkPermission(array(2))){
                                    echo "<small>System Administrator</small>";
                                }else{
                                    echo"<small>Forum Moderator</small>";
                                }?>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Settings</a>
                            </div>
                            <div class="pull-right">
                                <a href="/logout.php" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->

            </ul>
        </div>
    </nav>
</header>

