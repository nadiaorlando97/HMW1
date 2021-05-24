<?php
include 'funzioni.php';
session_start();


if ($_POST['fetchRequest']=="Grid"){

    $conn = database_connect();  

    $sql = "SELECT * FROM articoli";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)==0){
        echo ("Nessun articolo esistente"); 
    }else{
        mysqli_close($conn);
        $json=mysqli_fetch_all($result, MYSQLI_ASSOC);
        exit(json_encode($json));
    } 
}

if ($_POST['fetchRequest']=="Session"){

    if(isset($_SESSION['login_user'])){
        exit ("True");
    }else{
        exit ($_SESSION['login_user']);
    }
}

if ($_POST['fetchRequest']=="checkEmail"){

    $conn = database_connect();  
    $email=$_POST['email'];
    $admin=$_POST['admin'];
    
    if($admin=="true"){
        $sql = "SELECT * FROM admin WHERE utente='$email' FOR UPDATE";
    }else{
        $sql = "SELECT * FROM utenti WHERE Utente='$email' FOR UPDATE";  
    }
    
    $result = mysqli_query($conn,$sql);
    echo json_encode(array('exists' => mysqli_num_rows($result) > 0 ? true : false));
    mysqli_close($conn);
}



?>