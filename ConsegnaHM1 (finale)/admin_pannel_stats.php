<?php
    session_start();
    ini_set("display_errors","1");
    error_reporting(E_ALL);
    header('Access-Control-Allow-Origin: *');
    include 'funzioni.php';
    
    if (isset($_SESSION['login_admin'])) {
        
        if (!empty($_SESSION['login_admin'])) {
            
            $conn = database_connect();
            $results = array();
            
            $query_stats = "SELECT * FROM acquisto
                WHERE id IN (
                    SELECT id 
                    FROM acquisto as a, utenti as u
                    WHERE a.utente = u.CodUtente AND u.spesaTotSpedizioni > 100 AND a.conBuono = FALSE
                    )";
            $res_query_stats = mysqli_query($conn, $query_stats);

            while($row = mysqli_fetch_assoc($res_query_stats)){

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