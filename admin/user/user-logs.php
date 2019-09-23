<?php
include("../../../../models/config.php");

$items = 35;
$page = 1;

if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
    $limit = " LIMIT ".(($page-1)*$items).",$items";
else
    $limit = " LIMIT $items";

$sqlStr = "SELECT * FROM `forum_user_logs` ORDER BY TIMESTAMP desc";
$sqlStrAux = $mysqli->query("SELECT * FROM `forum_user_logs` ORDER BY TIMESTAMP desc");

$query = $mysqli->query($sqlStr.$limit);

$p = new pagination;
$p->Items($sqlStrAux->num_rows);
$p->limit($items);
$p->target("/forum/admin/user/user-logs.php");//#page/1/
$p->changeClass("pagination pull-right");
$p->urlFriendly(false);
$p->currentPage($page);
$p->calculate();

$action = $loggedInUser->username.' visited the user log page.';
$page = basename(__FILE__,".php").'.php';
$level = 6;
userForumLogs($page, $action, $level);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SS Admin Panel - User Logs</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

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
    include("functions/inc/header.php");
    ?>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <?php
    include("functions/inc/left-nav.php");
    ?>
    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Logs
                <small>View recent user activity</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Examples</a></li>
                <li class="active">Blank page</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box box-success">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Page</th>
                                    <th>Action</th>
                                    <th>IP</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if($query->num_rows == 0){
                                        echo "<tr><td colspan='5'>No logs could not be found for this user.</td></tr>";
                                    }else{
                                        while ($v1 = $query->fetch_object()){
                                            echo"<tr>";
                                                echo"<td>".getUsernameFrmId($v1->user_id, "forum_user_logs")."</td>";
                                                echo"<td>".$v1->page."</td>";
                                                echo"<td>".$v1->action."</td>";
                                                echo"<td>".$v1->ip."</td>";
                                                echo"<td>".date("d M h:ia", $v1->timestamp)."</td>";
                                            echo"</tr>";
                                        }
                                    }

                                ?>
                            </tbody>
                        </table>
                    </div>
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

    <!-- Control Sidebar -->

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
