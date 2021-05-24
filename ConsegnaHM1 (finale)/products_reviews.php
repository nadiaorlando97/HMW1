<?php
    session_start();
    ini_set("display_errors","1");
    error_reporting(E_ALL);
    header('Access-Control-Allow-Origin: *');
    include 'funzioni.php';
    
    if (isset($_SESSION['login_user'])) {
        
        if (!empty($_SESSION['login_user'])) {
            
            $conn = database_connect();
            $results = array();
            
            $query = "SELECT * FROM recensioni";
            $res_query = mysqli_query($conn, $query);

            while($row = mysqli_fetch_assoc($res_query)){

                $results[] = $row;
            }

            echo json_encode($results);

        }else{
            echo "";
        }
    }else{
        echo "";
    }
?>