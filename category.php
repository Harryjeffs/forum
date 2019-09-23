<?php


include_once('models/config.php');

$title = "View Forum";

$sub_cat_id = $_GET['id'];
    if(!is_integer($sub_cat_id)){
        header("/forum?error=id");
    }



if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
    $limit = " LIMIT ".(($page-1)*$items).",$items";
else
    $limit = " LIMIT $items";

$sqlStr = "SELECT 
       *
      FROM  `forum_thread` 
      WHERE sub_category_id = ".$sub_cat_id." and deleted = false
      ORDER BY `pinned` DESC, `thread_id` DESC";
$sqlStrAux = $mysqli->query("SELECT 
       *
      FROM  `forum_thread` 
      WHERE sub_category_id = ".$sub_cat_id." and deleted = false
      ORDER BY `pinned` DESC, `thread_id` DESC");

//$sqlStr = "SELECT * FROM Transfer ORDER by id DESC";
//$sqlStrAux = $mysqli->query("SELECT * FROM Transfer ORDER by id DESC");

$query = $mysqli->query($sqlStr.$limit);


$permQ = $mysqli->query("SELECT `permission_id` FROM `forum_sub_category` WHERE `sub_category_id` = '$sub_cat_id'");
$permR = $permQ->fetch_array();

$permissionID = $permR['permission_id'];

$sub_cat_infoQ = $mysqli->query("SELECT sub_category_desc, sub_category_long_desc FROM `forum_sub_category` WHERE sub_category_id = $sub_cat_id");
$stuff = $sub_cat_infoQ->fetch_array();
?>

<!doctype html>
<!--suppress HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget -->
<html>
<?php include('models/header.inc.php');?>
<?php include ("inc/top-banner.php");?>
<link rel="stylesheet" href="./assets/css/forum.css?v=183">
        <div class="container" style="margin-top: 100px;">
            <?php
            if($sub_cat_infoQ->num_rows == 0){
                echo"<div class='alert alert-warning'>This category does not exist. Please return to the <a href='/forum'>forum home</a></div>";
            }else if($loggedInUser->checkPermission(array($permissionID))) {

            ?>
            <div class="jumbotron">
                <h1><?php echo $stuff['sub_category_desc']; ?></h1>
                <p><?php echo $stuff['sub_category_long_desc']; ?></p>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-md-10">
                    <ol class="breadcrumb">
                        <li><a href="<?php echo FULL_PATH?>/index.php">Forum Home</a></li>
                        <li class="active"><?php echo $stuff['sub_category_desc']; ?></li>
                    </ol>
                </div>
                <div class="col-md-2"><a href="<?php echo FULL_PATH?>/new/<?php echo $sub_cat_id; ?>" class="btn btn-block btn-success"
                                         style="min-height: 37px; padding-top: 8px;">New Thread</a></div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#" class="toggle-threads active"><i
                            class="ion-ios-paper"></i> <?php echo $sqlStrAux->num_rows; ?> Discussions</a>
                </div>
                <div class="panel-body">
                    <ul class="threads-listing">
                        <div class="loading-div"><img src="<?php echo FULL_PATH;?>./assets/img/ajax-loader.gif" style="margin-top: 20%; margin-left: 50%;"></div>
                        <div id="results"></div>
                    </ul>
                </div>
            </div>
        </div>
           <div id="cat-pag"></div>
            <br/>
        <?php
    }else{
        echo"<div class='alert alert-warning'>You do not have access to view this category. Please return to the <a href='/forum'>forum home</a></div>";
    }
?>

<?php include("models/footer.inc.php"); ?>
<script>
    sub_cat_id =  <?php echo $sub_cat_id; ?>;
    $(document).ready(function(){
            page = 1;
            $(".loading-div").hide();
            $("#results").load(base_url + "/inc/pages/view_category.php", {
                id: sub_cat_id,
                page: page
            }, function (data) {
                $contents = $(data);
                pag = $('center', $contents[4]);
                $("#cat-pag").html($contents[4]);
                $("center").first().remove();
                $('[data-toggle="popover"]').popover({html: true});
                $('[data-toggle="tooltip"]').tooltip();
            });

    });
</script>
</body>
</html>
