<?php
include("../../../models/config.php");

$edit_id = intval($_GET['edit_id']);

    $editq = $mysqli->query("SELECT * FROM `forum_posts_edits` WHERE edit_id = $edit_id");
    $edit = $editq->fetch_object();

// include the Diff class
include '../../../models/class/class.textdif.php';

$old_text = $edit->old_text;
$new_text = $edit->new_text;

?>
<style type="text/css">
    div.old-text ins {  background: transparent; }
    div.new-text del {background: transparent;}

    del{
        background-color: #ff9999;
        text-decoration: none;
    }
    ins{
        background-color: rgb(193, 240, 193);
        text-decoration: none;
    }
</style>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Blank Page</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

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
    include("../../functions/inc/header.php");
    ?>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <?php
    include("../../functions/inc/left-nav.php");
    ?>
    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Post Edits
                <small>View what has been edited in this post</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/forum/admin/index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="recent-edits.php">Edit List</a></li>
                <li class="active">placeholder</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <!-- Default box -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Old Text</h3>
                            <br>
                            <small>In red is deleted text!</small>
                        </div>
                        <div id="htmldiff">
                             <div class="box-body old-text">
                                    <?php
                                    $post_content = preg_replace('@\x{FFFD}@u', '', $edit->old_text);

                                    $mention = new mentions();

                                    $userf = fetchAllUsers();

                                    foreach ($userf as $users1){
                                        $mention->add_name($users1['user_name']);
                                    }

                                    $opcodes = FineDiff::getDiffOpcodes($old_text, $new_text, FineDiff::$wordGranularity);

                                    $diff = FineDiff::renderDiffToHTMLFromOpcodes($old_text, $opcodes);

                                    $parser = new \SBBCodeParser\Node_Container_Document();


                                    $parser->add_emoticons(array(
                                        ':)' => 'emoji/1F603.png',
                                        ':D' => 'emoji/1F604.png',
                                        'xD' => 'emoji/1F632.png'
                                    ));
                                    $post_content = $parser->parse($diff)
                                        ->detect_links()
                                        ->detect_emails()
                                        ->detect_emoticons()
                                        ->get_html();

                                    echo $mention->process_text($post_content);
                                     ?>
                             </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <div class="col-md-6">
                    <!-- Default box -->
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">New Text</h3>
                            <br>
                            <small>In green is new text!</small>

                        </div>
                        <div id="htmldiff">
                            <div class="box-body new-text">
                                <?php
                                $post_content = preg_replace('@\x{FFFD}@u', '', $edit->new_text);

                                $mention = new mentions();

                                $userf = fetchAllUsers();


                                foreach ($userf as $users1){
                                    $mention->add_name($users1['user_name']);
                                }
                                $opcodes = FineDiff::getDiffOpcodes($old_text, $new_text, FineDiff::$wordGranularity);

                                $diff = FineDiff::renderDiffToHTMLFromOpcodes($new_text, $opcodes);

                                $parser = new \SBBCodeParser\Node_Container_Document();

                                $parser->add_emoticons(array(
                                    ':)' => 'emoji/1F603.png',
                                    ':D' => 'emoji/1F604.png',
                                    'xD' => 'emoji/1F632.png'
                                ));
                                $post_content = $parser->parse($diff)
                                    ->detect_links()
                                    ->detect_emails()
                                    ->detect_emoticons()
                                    ->get_html();

                                echo $mention->process_text($post_content); ?>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
</body>
</html>
