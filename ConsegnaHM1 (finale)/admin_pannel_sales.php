<?php
    session_start();
    ini_set("display_errors","1");
    error_reporting(E_ALL);
    header('Access-Control-Allow-Origin: *');
    include 'funzioni.php';
    
    if (isset($_SESSION['login_admin'])) {
        
        if (!empty($_SESSION['login_admin'])) {
            
            $conn = database_connect();
            
            $query_modifica_sconti = "UPDATE articoli SET Sconto = CASE  
                WHEN Prezzo >= 500 THEN 50 
                WHEN Prezzo >= 300 AND Prezzo < 500 THEN 30
                WHEN Prezzo >= 100 AND Prezzo < 300 THEN 10
                ELSE 0
                END";
            $query_applica_sconti = "UPDATE articoli SET PrezzoScontato = Prezzo * (1 - Sconto/100)";
            $res_query1 = mysqli_query($conn, $query_modifica_sconti);
            $res_query2 = mysqli_query($conn, $query_applica_sconti);
            if($res_query1 and $res_query2){
                echo "succes";
            }
        }else{
            echo "";
        }
    }else{
        echo "";
    }
?>