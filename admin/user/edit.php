<?php
include("../../../models/config.php");

$user_id = intval($_GET['id']);

if(empty($user_id) or !is_numeric($user_id)) {
    header("Location: view.php");
}

$userdetails = fetchUserDetails(NULL, NULL, $user_id);


$action = $loggedInUser->username.' viewed an edit log';
$page = basename(__FILE__,".php").'.php';
$level = 6;
userForumLogs($page, $action, $level);

$userPermission = fetchUserPermissions($user_id);

if($loggedInUser->checkPermission(array(2))){
    $permissionData = fetchAllAdminPermissions();

}else{
    $permissionData = fetchAllModPermissions();
}
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
    <link rel="stylesheet" type="text/css" href="/forum./assets/css/pnotify.custom.min.css">

    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href=".../assets/css/datepicker.css">
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
                Edit <?php echo $userdetails['user_name']; ?>'s profile
                <small>Also few all the small stats.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/forum/admin/index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="view.php">View Users</a></li>
                <li class="active"><?php echo $userdetails['display_name']?>'s profile</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-8">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                    <h3 class="box-title">General</h3>
                                </div>
                            <div class="box-body">
                                <form action="" method="post" id="admin-edit-form">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#general">Details</a></li>

                                        <li><a data-toggle="tab" href="#portal">Forum Permissions</a></li>
                                        <li><a data-toggle="tab" href="#activity">Recent Activity</a></li>
                                        <li><a data-toggle="tab" href="#punish">Punish</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="general" class="tab-pane fade in active">
                                            <h3>User Information</h3>
                                            <p>
                                            <div class='form-group'>
                                                <label for='username_new'>Username:</label>
                                                <input type='text' name='new_username' value="<?php echo $userdetails['user_name']; ?>" class='form-control'>
                                            </div>
                                            <div class='form-group'>
                                                <label>Display Name:</label>
                                                <input  class='form-control' type='text' name='display' value='<?php echo $userdetails['display_name']; ?>' />
                                            </div>
                                            <div class='form-group'>
                                                <label>Email:</label>
                                                <input class='form-control' type='text' name='email' value='<?php echo $userdetails['email']; ?>' />
                                            </div>
                                            <div class='form-group'>
                                                <label>Rank:</label>
                                                <input class='form-control' type='text' name='rank' value='<?php echo $userdetails['rank']; ?>' />
                                            </div>
                                            <div class='form-group'>
                                                <label>Promotion Tag:</label>
                                                <input class='form-control' type='text' name='tag' value='<?php echo $userdetails['promo_tag']; ?>' />
                                            </div>
                                            <input type='hidden' name='title' value='<?php echo $userdetails['title']; ?>'>

                                            </p>
                                        </div>
                                        <div id="portal" class="tab-pane fade">
                                            <h3>Permission Membership</h3>
                                            <p>
                                                <?php
                                                switch ($userdetails['user_name']) {
                                                    case "admin1":
                                                        echo "You cannot edit user permissions for this user.";
                                                        break;
                                                    case "genr":
                                                        echo "You cannot edit user permissions for this user.";
                                                        break;
                                                    default:

                                                        echo "<div class='form-group'>Remove Permission:";
                                                        //List of permission levels user is apart of
                                                        foreach ($permissionData as $v1) {
                                                            if (isset($userPermission[$v1['id']])) {
                                                                echo "<br><input type='checkbox' name='removePermission[" . $v1['id'] . "]' id='removePermission[" . $v1['id'] . "]' value='" . $v1['id'] . "'> " . $v1['name'];

                                                            }
                                                        }

                                                        //List of permission levels user is not apart of
                                                        echo "</div><div class='form-group'>Add Permission:";
                                                        foreach ($permissionData as $v1) {
                                                            if (!isset($userPermission[$v1['id']])) {
                                                                echo "<br><input type='checkbox' name='addPermission[" . $v1['id'] . "]' id='addPermission[" . $v1['id'] . "]' value='" . $v1['id'] . "'> " . $v1['name'];
                                                            }
                                                        }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="forum" class="tab-pane fade">
                                        <h3>Ooops!</h3>
                                        <p>
                                        <div class="alert alert-warning">Forum permissions and information related to the forum has moved to the forum admin page. To view this user's information, please <a href="/forum/admin/user/<?php echo $id;?>">clicking here.</a></div>
                                        </p>
                                    </div>
                                    <div id="activity" class="tab-pane fade">
                                        <h3>Ooops!</h3>
                                        <p>
                                            This user has been up to nothing lately. Check back soon to see if they have been active.
                                        </p>
                                    </div>
                                    <div id="punish" class="tab-pane fade">
                                        <h3>Punish <?php echo $userdetails['user_name'];?> </h3>
                                        <p>Use this form to disable forum privileges for certain users based on their actions. </p>
                                            <div class="form-group">
                                                <label for="punishment">Type of Punishment</label>
                                                <select id="punishmentType" class="form-control" name="punishment">
                                                    <option value="1" selected>Formal Warning</option>
                                                    <option value="2">2 Hour Probation</option>
                                                    <option value="3">24 Hour Probation</option>
                                                    <option value="4">1 Week Probation</option>
                                                    <option value="5">Permanent Ban</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="disable">Disable Forum Features</label>
                                                <?php
                                                $disable_num = $mysqli->query("SELECT `disable_id`, `user_id` FROM `forum_disable_user` WHERE `user_id` = $user_id")->num_rows;
                                                    if ($disable_num > 0) {
                                                        ?>
                                                            <div class="alert alert-warning">This user already their privileged revoked. Please select no if you do not need to increase their current discipline status.</div>
                                                        <?
                                                    }
                                                ?>
                                                <div class="radio">
                                                    <label><input type="radio" name="optradio" value="Yes" id="radioYes">Yes</label>
                                                </div>
                                                <div class="radio">
                                                    <label><input type="radio" name="optradio" value="No" checked>No</label>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display: none;" id="hidden-disable">
                                                <select name="disable" id="disable" class="form-control">
                                                    <option value="2" selected>Disable Forum Replying</option>
                                                    <option value="3">Disable Creating Thread Creation</option>
                                                    <option value="4">Disable Forum PM</option>
                                                    <option value="5">Disable All Features</option>
                                                </select>
                                                <br>
                                                    <div class="form-group">
                                                        <label for="datetimepicker1">Expiry Date</label>
                                                        <div class='input-group date' id='datetimepicker1'>
                                                            <input type='text' name="datetimepicker" class="form-control" />
                                                            <span class="input-group-addon">
                                                             <span class="glyphicon glyphicon-calendar"></span>
                                                             </span>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="reason">What did they do? Please provide full detail.</label>
                                                <br><small>Please note the user will see this message.</small>
                                                <textarea name="reason" id="action" cols="30" rows="10" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <p class="text-red">Please note that these actions cannot be overturned. Any misuse of the punishment system will result in lose of admin privileges. </p>
                                                <br>
                                            </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <input class='btn btn-primary submit' name='admin_user' type='submit' value='Update' />
                    <br />
                    <br />
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border"><h3 class="box-title">Account details</h3></div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">User id:</label> <?php echo $userdetails['id']; ?>
                            </div>
                            <div class="form-group">
                                <label for="">Total Infractions: </label> <?php if(totalInfractionsuser($user_id) >= 10 ){ echo '<span class="label label-danger">'.totalInfractionsuser($user_id).' Infractions</span>';}elseif (totalInfractionsuser($user_id) >= 5 ){ echo '<span class="label label-info">'.totalInfractionsuser($user_id).' Infractions</span>';}else{ echo '<span class="label label-success">'.totalInfractionsuser($user_id).' Infractions</span>';} ?>
                            </div>

                            <div class="form-group">
                                <label for="">Total Posts:</label> <?php echo totalUserPosts($userdetails['id']);; ?>
                            </div>
                            <div class="form-group">
                                <label for="">Last Login IP:</label> <?php echo $userdetails['updatedip']; ?>
                            </div>
                            <div class="form-group">
                                <label for="">Last Sign In:</label>
                                <?php
                                if ($userdetails['last_sign_in_stamp'] == '0'){
                                    echo "Never";
                                }else{
                                    $timeSince = lastSignUp($userdetails['last_sign_in_stamp']);
                                    if($timeSince >=1209600){
                                        echo'<a href="#" data-placement="right" style="color: inherit;" data-trigger="hover" data-toggle="popover" id="timeSince" title="Last Log In" data-content="' . humanTiming($userdetails['last_sign_in_stamp']) . '"><span style="color: orange;">' . date("j M, Y", $userdetails['last_sign_in_stamp']) . '</span></a>';
                                    }else{
                                        echo'<a href="#" data-placement="right" style="color: inherit;" data-trigger="hover" data-toggle="popover" id="timeSince" title="Last Log In" data-content="' . humanTiming($userdetails['last_sign_in_stamp']) . '"><span style="color: black;">' . date("j M, Y", $userdetails['last_sign_in_stamp']) . '</span></a>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                    <label for="">Active: </label> <?php  if ($userdetails['active']=='1'){echo "<span style='color: green'' > Yes</span>";}else{echo "<span style='color:red''> No</span> </div><div class='form-group'> <input type='submit' class='btn btn-primary' name='activate' id='activate' value='Activate'> ";}?>
                            </div>
                            <div class="form-group">
                                <?php
                                    if($userdetails["lost_password_request"] == 1){
                                        echo '
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="This user currently has an outstanding password reset request meaning their password cannot be reset at this time.">
                                                <button class="btn btn-primary" style="pointer-events: none;" type="button" disabled>Reset password</button>
                                            </span>
                                        ';
                                    }else{
                                        echo '<button class="btn btn-primary" id="resetUserPass">Reset Password</button>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
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
<!--custom jS-->
<script src=".../assets/js/ajax.js?id=38"></script>
<!-- Pnotify -->
<script src="/forum./assets/js/pnotify.custom.min.js"></script>

<script src="../.../assets/js/moment.js"></script>
<script src=".../assets/js/datepicker.js"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip()
        $("input[name='optradio']").change(function(){
            if(this.value == "Yes"){
                $("#hidden-disable").fadeIn(700).show();
            }
            if(this.value == "No"){
                $("#hidden-disable").fadeOut(700).hide();
            }
        });
            $('#datetimepicker1').datetimepicker({
                sideBySide: true,
                showClose: true,
                keepOpen: true,
                minDate: moment().add(2, 'hours')
            });
    });
</script>
<script>
    user_id = <?php echo $user_id;?>;
    base_url = "<?php echo FULL_PATH; ?>";
    loggedInUserUsername = "<?php echo $loggedInUser->username; ?>";
    loggedInUserId = "<?php echo $loggedInUser->user_id; ?>";
</script>
</body>
</html>
