<?php

function database_connect(){
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "luxuryshopping";
    $db = mysqli_connect($host, $user, $password, $database);
    
    if(!$db) {
         header('Location: errore.php?msg='.urlencode("Errore di connessione al database! Impossibile accedere al sito"));
     }
   else{
       return $db;  
 }
}

/*La sessione scade dopo 2 minuti quindi al termine è necessario effettuare nuovamente
il login*/
function check_login(){
    $t=time(); 
    $diff=0; 

    if (isset($_SESSION['time'])){
        $t0 = $_SESSION['time']; 
        $diff=($t-$t0); //differenza tra il momento in cui avviene il login e quello in cui controllo se sono trascorsi 2 minuti
    } 
    
    if (($diff > 120)) {
        session_destroy();
        header('Location: index.php?msg='.urldecode("Sessione scaduta! Fai il login"));         
    } else {
        $_SESSION['time']=time(); //se non sono trascorsi 2 minuti aggiorno la variabile time con il tempo corrente
    }
}

function dist_session(){
    session_destroy(); 
    header('Location: index.php?msg='.urlencode("Logout avvenuto con successo"));
}

/*Effettua un reindirizzamento alla stessa pagina HTTPS*/
function checkHTTPS(){

     if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'){
         //la richiesta � su https e va bene 
      }      else
      {
         $redirect = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
         header('HTTP/1.1 301 Moved Permanently');
         header("Location: ".$redirect);
          exit();
      }
    
 }




?>