<?php
include("../../models/config.php");

$items = 15;
$page = 1;

if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
    $limit = " LIMIT ".(($page-1)*$items).",$items";
else
    $limit = " LIMIT $items";

$sqlStr = "SELECT * FROM `forum_posts_edits`";
$sqlStrAux = $mysqli->query("SELECT * FROM `forum_posts_edits`");

$query = $mysqli->query($sqlStr.$limit);
;
$p = new pagination;
$p->Items($sqlStrAux->num_rows);
$p->limit($items);
$p->target("/forum/admin/user/recent-edits.php");//#page/1/
$p->urlFriendly(false);
$p->currentPage($page);
$p->calculate();

$action = $loggedInUser->username.'visited the recent edit list';
$page = basename(__FILE__,".php").'.php';
$level = 6;
userForumLogs($page, $action, $level);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Blank Page</title>
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
                Post Edits
                <small>View recent post edits</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/forum/admin/index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Edit's</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Title</h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Post</th>
                            <th>User</th>
                            <th>Time</th>
                            <th>Managed</th>
                            <th></th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($fetched = $query->fetch_array()){
                            echo"<tr>";
                            echo"<td><a href='/forum/thread/".$fetched['thread_id']."/#".$fetched['post_id']."'>".$fetched['edit_id']."</a></td>";
                            echo"<td>".getUsername($fetched['user_id'],"forum_thread")."</td>";
                            echo"<td>".date('l, F j Y, g:ia', $fetched['timestamp'])."</td>";
                            if($fetched['managed'] == 1){
                                echo"<td><span class=\"label label-warning\">Pending</span></td>";
                            }elseif ($fetched['managed'] == 2){
                                echo"<td><span class=\"label label-danger\">Punished</span></td>";
                            }else{
                                echo"<td><span class=\"label label-success\">Managed</span></td>";
                            }
                            echo"<td><a href='edit/index.php?edit_id=".$fetched['edit_id']."&action=4'><button class='btn btn-primary'>View Edit</button></a></td>";
                            echo"</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <?php $p->show(); ?>
                </div>
                <!-- /.box-footer-->
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

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="//bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
</body>
</html>
