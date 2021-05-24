<?php
include 'funzioni.php';
checkHTTPS();

if(isset($_POST['invia'])){
    
    $email = $_POST['email'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $admin = $_POST['admin'];
    
    $checkminuscolo = preg_match('/[a-z]/',$pass1);
    $checkmaiuscolo = preg_match('/[A-Z]/',$pass1);
    $checknumero = preg_match('/[0-9]/',$pass1);
    $checkemail = preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',$email);
     
    //Dopo aver effettuato alcuni controlli in javascript rieseguo i controlli in php per maggiore sicurezza ed evitare
    // di caricare qualcosa di sbagliato all'interno del db

    if(empty($email)||empty($pass1)|| empty($pass2)){           
        header("Location: registrazione.php?msg=".urlencode("Compila tutti i campi"));
    }else if(strlen($pass1) < 5){
        header("Location: registrazione.php?msg=".urlencode("Password troppo corta"));
    }else if(!$checkemail){
        header("Location: registrazione.php?msg=".urlencode('Registrazione non Riuscita! inserisci un email valida'));
    }else if(!($checkminuscolo && $checkmaiuscolo && $checknumero )){
        header("Location: registrazione.php?msg=".urlencode('Registrazione non Riuscita! La password deve contenere almeno un carattere alfabetico minuscolo, un carattere alfabetico maiuscolo e un carattere numerico'));
    }else if($pass1!=$pass2){
        header("Location: registrazione.php?msg=".urlencode("Le due password devono coincidere"));
    }else{
        $conn = database_connect(); 
        $email = mysqli_real_escape_string($conn,$email);
        
        $sale = bin2hex(openssl_random_pseudo_bytes(10));
        $password = $pass1.$sale;
        $pass1 = md5($password);
        
        if ($admin){
            $sql = "INSERT into admin (utente,password,Sale) VALUE ('$email','$pass1','$sale')";
        }else{
            $sql = "INSERT into utenti (Utente,Password,Sale) VALUE ('$email','$pass1','$sale')";
        }
        
        if(!(mysqli_query($conn,$sql))){               
            header("Location: index.php?msg=".urlencode("Errore! Registrazione fallita"));
        }else{                
            header("Location: index.php?msg=".urlencode("Registrazione effettuata")); 
        }

        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="it">     
    <head>
        <meta charset="utf-8" />
        <title>Registrazione</title>
        <link href="registrazione.css" rel="stylesheet" type="text/css"/> 
        <script type="text/javascript" src='script.js' defer="true"></script>
    </head>
<body>
    <noscript>
            <div><h2>Il tuo browser non supporta o ha disabilitato javascript </h2>
            	<h1> Il sito non funzionera correttamente</h1>
            </div>
    </noscript>
		
        <h2 id="titolo">Registrazione</h2>
 <?php    
        if(isset($_REQUEST['msg'])){             
        $msg = $_REQUEST['msg'];
        $msg = strip_tags($msg);	
 ?>

        <div id="messaggio"><?php echo $msg; ?></div>  

 <?php }?> 

     	
     	<div id="pannello">
            <form action="" method="post">
                <p>
                    <input type = 'checkbox' name='admin' value='Admin' id="mycheck" onchange="clickAdmin()">
                    <label>Clicca qui se sei un admin del sito </label> <span id="checkBox"></span>  
                </p>  
                <p>
                    <label>Email:</label> <span id="logemail"></span>
                    <input type="email" id="email" onkeyup="checkEmail(this.value)" name="email"/> 
                </p>
                <p >
                    <label>Password:</label><span id="p1"></span>
                    <input type="password" id="pass1" onkeyup="checkPsw(this.value)" name="pass1" />
                   
                </p>
                <p >
                    <label>Ripeti password:</label><span id="p2"></span><span id="p3"></span>
                    <input type="password" id="pass2" onkeyup="checkPsw1(this.value)" name="pass2"   />
                </p>
                <p>
                    <input type="submit" name="invia" class="btn" value="Registrati" />
                </p>
               
            </form>
            
            <form action="index.php" method="get">
                    <p>
                        <input type="submit" class="btn" value="Home" />
                    </p>
            </form>
            <p>
                La password deve contenere almeno un carattere alfabetico minuscolo, un carattere alfabetico maiuscolo e un numero
            </p>
        </div> 

        <footer>
           <p></p>
        </footer>  
</body>
</html>