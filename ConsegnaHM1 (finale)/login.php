<?php
include 'funzioni.php';
checkHTTPS();

if (!empty($_POST['email']) && !empty($_POST['pass'])){
    
    $conn = database_connect();
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $email = strip_tags($email);
    $pass = $_POST['pass'];

    //Se stiamo provando ad accedere come admin effettuiamo le query alla tabella admin
    //Se invece proviamo ad accedere come utente affettuiamo le query alla tabella utente 
    if ($_POST['admin']==true){
        $sql = "SELECT Sale FROM admin WHERE utente = '".$email."'";

        $result = mysqli_query($conn, $sql);
        if(!$result){
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Impossibile completare il login"));   
        }

        if(mysqli_num_rows($result)==0){
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Email o password non validi"));   
        }

        $sale = mysqli_fetch_assoc($result);
        $password = $sale['Sale'];
        $id = $sale['codAdmin'];
        $password = $pass.$password;
        $pass = md5($password);
        $sql = "SELECT * FROM admin WHERE utente ='".$email."' AND password='".$pass."'";
        $result = mysqli_query($conn, $sql);

        if(!$result){
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Impossibile completare il login"));
        }

        if(mysqli_num_rows($result)==0){
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Email o password non validi"));   
        }   

        if(mysqli_num_rows($result)>1){ 
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Impossibile completare il login"));
        }
        if(mysqli_num_rows($result)==1) { 
            session_start();
            $_SESSION['login_admin']= $email;
            $_SESSION['id'] = $id; 
            $_SESSION['time']=time();
            header('Location: index.php?msg='.urlencode("Login admin effettuato con successo")); 
        }
        mysqli_close($conn);

    }else{

        $sql = "SELECT Sale FROM utenti WHERE Utente = '".$email."'";
        $result = mysqli_query($conn, $sql);
        if(!$result){
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Impossibile completare il login"));   
        }

        if(mysqli_num_rows($result)==0){
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Email o password non validi"));   
        }

        $sale = mysqli_fetch_assoc($result);
        $password = $sale['Sale'];
        $id = $sale['CodUtente'];
        $password = $pass.$password;
        $pass = md5($password);
        $sql = "SELECT * FROM utenti WHERE Utente ='".$email."' AND Password='".$pass."'";
        $result = mysqli_query($conn, $sql);

        if(!$result){
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Impossibile completare il login"));
        }

        if(mysqli_num_rows($result)==0){
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Email o password non validi"));   
        }   

        if(mysqli_num_rows($result)>1){ 
            header('Location: Login.php?msg='.urlencode("Errore in fase di login. Impossibile completare il login"));
        }

        if(mysqli_num_rows($result)==1) { 
            session_start();
            $_SESSION['login_user'] = $email; 
            $_SESSION['id'] = $id; 
            $_SESSION['time']=time();
            header('Location: index.php?msg='.urlencode("Login utente effettuato con successo"));
        }
        mysqli_close($conn);
    }

}else if (isset($_POST["email"]) || isset($_POST["pass"])) {
    // Se solo uno dei due è impostato
    header('Location: Login.php?msg='.urlencode("Inserisci username e password."));
}


?>
<html lang="it" >

<head>
    <meta charset="utf-8" /> 
    <title>Login</title>
    <link href="login.css" rel="stylesheet" type="text/css"/> 
</head>

<body >
    <noscript>
            <div><h2>Il tuo browser non supporta o ha disabilitato javascript </h2>
                    <h1> Il sito non funzionera correttamente</h1>
            </div>
    </noscript>
	<h2 id="titolo">Login</h2>
	
<?php    
        if(isset($_REQUEST['msg'])){  
         $msg = $_REQUEST['msg'];
         $msg = strip_tags($msg);	
            
?>
        	<div id="messaggio"><?php echo $msg; ?></div>
<?php }?>  
      	
    <div id="pannello">

      	<form action="" method="post">
            <div>
                <p>
                    <input type = 'checkbox' name='admin' value='Admin' id="mycheck">
                    <label>Clicca qui se sei un admin del sito </label> <span id="checkBox"></span>  
                </p>  
            </div>
            <div>
        	    <p>
                	<label> Email: </label>
                    <input type="email" id="email" name="email"/>       
                </p>
            </div>
            <div>
               <p>
                  	<label>Password:</label>
                    <input type="password" id="pass" name="pass" />            
               </p>
            </div>
            <p>
            	<input type="submit" class="btn" value="Login" />
            </p>
        </form>

        <form action="index.php" method="get">
        	<p>
            	<input type="submit" class="btn" value="Home"/> 
            </p>
        </form>

        <form action="registrazione.php" method="get">
        	<p>Non hai un account?</p>
        <p>
            <input type="submit" class="btn" value="Iscriviti"/> 
        </p>
        </form>
    </div>
	  
</body>
</html>