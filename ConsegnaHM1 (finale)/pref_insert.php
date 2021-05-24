<?php
session_start();
ini_set("display_errors","1");
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
include 'funzioni.php';

if (isset($_SESSION['login_user'])) {
    
    if (!empty($_SESSION['login_user'])) {
        
        $conn = database_connect();
        $codProdotto = $_GET["codProdotto"];
      
        $username = $_SESSION['login_user'];
     
        $query = "SELECT * FROM utenti WHERE Utente ='".$username."'";
        $result = mysqli_query($conn , $query);
        $row = mysqli_fetch_assoc($result);
        $user_id = $row["CodUtente"];
    
        $query = "INSERT INTO preferiti (RefCodProdotto, RefCodUtente) VALUES ('".$codProdotto."', '".$user_id."')";
        $res_query = mysqli_query($conn, $query);

        if($res_query){
            $msg = "succes";
            echo $msg;
        }else{
            $msg = "error";
            echo $msg;
        }

    }else{
        $msg = "error";
        echo $msg;
    }
}else{
    $msg = "error";
    echo $msg;
}
?>




