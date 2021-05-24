<?php 

if(isset($_REQUEST['msg'])){
    $msg = $_REQUEST['msg'];
    $msg = strip_tags($msg);	
    echo $msg;   
}

?>