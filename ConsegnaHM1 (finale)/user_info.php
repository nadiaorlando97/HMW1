<?php
    session_start();
    ini_set("display_errors","1");
    error_reporting(E_ALL);
    header('Access-Control-Allow-Origin: *');
    include 'funzioni.php';
    
    if (isset($_SESSION['login_user'])) {
        
        if (!empty($_SESSION['login_user'])) {

            $results = array();
            
            $conn = database_connect();
            $username = $_SESSION['login_user'];
            $query = "SELECT * FROM utenti WHERE Utente ='".$username."'";
            $result = mysqli_query($conn , $query);
            $row = mysqli_fetch_assoc($result);
            $id = $row["CodUtente"];
            
            $query_media_acquisti = "SELECT conBuono, AVG(importo) as avg FROM `acquisto` WHERE utente = '".$id."' GROUP BY conBuono";
            $res_query_media_acquisti = mysqli_query($conn, $query_media_acquisti);
            
            $results[] = mysqli_fetch_assoc($res_query_media_acquisti);
            $results[] = mysqli_fetch_assoc($res_query_media_acquisti);

            $query_acquisti_sped_non_recenti = "SELECT * FROM acquisto WHERE utente = '".$id."' AND id IN (
                SELECT sc.acquisto 
                FROM spedizioneavvenuta as sa, spedizioniincorso as sc
                WHERE sc.acquisto=sa.acquisto AND DATEDIFF(CURRENT_DATE(), sa.dataConsegna) > 30 OR DATEDIFF(CURRENT_DATE(), sc.dataInvio) > 40
                )";
            $res_query_acquisti_sped_non_recenti = mysqli_query($conn, $query_acquisti_sped_non_recenti);
           
            while($row = mysqli_fetch_assoc($res_query_acquisti_sped_non_recenti)){
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