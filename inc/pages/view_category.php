<?php


include_once('../../models/config.php');

$sub_cat_id = $_POST['id'];

$items = 4;
$page = 1;


if(isset($_POST['page']) and is_numeric($_POST['page']) and $page = $_POST['page'])
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

$query = $mysqli->query($sqlStr.$limit);

$p = new pagination;
$p->Items($sqlStrAux->num_rows);
$p->limit($items);
$p->ajax(true);
$p->currentPage($page);
$p->calculate();


                if($sqlStrAux->num_rows == 0){
                    echo'      <ul class="threads-listing">
        <p style="text-align: center; margin-top: 15px; margin-bottom: 15px; color: rgba(0, 0, 0, 0.4)">There are no threads in this category yet. <a href="#">Be the first to create a thread</a></p></ul>';
                }else {
                    include 'drawups/category.php';
                    while ($cat_fetched = $query->fetch_array()) {
                        $category = new view_category();

                        $category->parse($loggedInUser->user_id, $sub_cat_id);
                       echo $category->value($cat_fetched);
                    }
                }
echo '  </ul>
                </div>
            </div>
        </div>
           <div id="cat-pag"></div>';
$p->show();
?>


