<?php
include("../../../models/config.php");

$users = fetchAllUsers();

$action = $loggedInUser->username.' visited the user list';
$page = basename(__FILE__,".php").'.php';
$level = 6;
userForumLogs($page, $action, $level);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>SS Admin Panel - View Users</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    include("../functions/inc/header.php");
    ?>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <?php
    include("../functions/inc/left-nav.php");
    ?>
    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                View Forum Users
                <small>Collective information, here.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/forum/admin/index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">View Users</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    <div class='table-responsive'>
                        <table class='table table-striped' id='example'>
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>Rank</th>
                                <th>Title</th>
                                <th>Infractions</th>
                                <th>Posts</th>
                                <th>Last Sign In</th>
                                <th>Active</th>
                            </tr>
                            </thead>
                            <?PHP
                            //Cycle through users
                            foreach ($users as $v1) {
                                $user_id = $v1['id'];
                                
                                echo "
                        <tr>";


                                echo"
                            <td><a  href='edit.php?id=".$v1['id']."'>".$v1['user_name']."</a></td>
                            ";
                                echo"<td>".$v1['rank']."</td>";

                                echo  "
                            ";
                                switch ($v1['title']) {

                                    case 'Main Administrator':
                                        echo "<td><span style='color:#ba0be0; background-image: url(assets/img/sparkle.gif);' > " . $v1['title'] . "</span></td>";
                                        break;
                                    case 'Administrator':
                                        echo "<td><span style='color:red;background-image: url(assets/img/sparkle.gif);'> " . $v1['title'] . "</span></td>";
                                        break;
                                    case 'Moderator':
                                        echo "<td><span style='color:orange;'> " . $v1['title'] . "</span></td>";
                                        break;
                                    case 'Senior Staff':
                                        echo "<td><span style='color:#00bfff'>" . $v1['title'] . "</span></td>";
                                        break;
                                    case 'Badge Admin':
                                        echo "<td></td>";
                                        break;
                                    default:
                                        echo "<td>" . $v1['title'] . "</td>";

                                }


                                if(totalInfractionsuser($user_id) == 0 ){ echo '<td><span class="label label-success">'.totalInfractionsuser($user_id).' Infractions</span></td>';}elseif (totalInfractionsuser($user_id) >= 1 ){ echo '<td><span class="label label-warning">'.totalInfractionsuser($user_id).' Infractions</span></td>';}else if(totalInfractionsuser($user_id) >= 10 ){ echo '<td><span class="label label-success">'.totalInfractionsuser($user_id).' Infractions</span></td>';}
                                echo "<td>".totalUserPosts($v1['id'])."</td>";
                                echo"
                            <td>
                                ";
                                $timeSince = lastSignUp($v1['last_sign_in_stamp']);

                                //Interprety last login
                                if ($v1['last_sign_in_stamp'] == '0') {
                                    echo "<font color='red'>Never</font>";
                                } else {

                                    if ($timeSince >= 1209600) {

                                        echo '<a href="#" data-placement="right" style="color: inherit;" data-trigger="hover" data-toggle="popover" id="timeSince" title="Last Log In" data-content="' . humanTiming($v1['last_sign_in_stamp']) . '"><span style="color: orange;">' . date("j M, Y", $v1['last_sign_in_stamp']) . '</span></a>';

                                    } else {
                                        echo '<a href="#" data-placement="right" style="color: inherit;" data-trigger="hover" data-toggle="popover" id="timeSince" title="Last Log In" data-content="' . humanTiming($v1['last_sign_in_stamp']) . '"><span style="color: black;">' . date("j M, Y", $v1['last_sign_in_stamp']) . '</span></a>';
                                    }
                                }
                                echo"</td>";
                                if($v1['active'] == 1){
                                    echo "<td><span class=\"label label-success label-block\">Yes</span></td>";
                                }else{
                                    echo "<td><span class=\"label label-danger label-block\">No</span></td>";
                                }
                                echo'
                            </td>

                        </tr>';
                            }

                            echo "
                    </table>";
                            ?>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.3.8
        </div>
        <strong>Copyright &copy; 2014-2016 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
        reserved.
    </footer>


    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
</body>
</html>
