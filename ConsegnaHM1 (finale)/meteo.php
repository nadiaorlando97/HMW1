
<?php 
    //Ritorna un JSON con i risultati dell'API selezionata
    
    $APIkey = '0c9ca3df42eda585a44501afb92cc5ec';
    $city_name = $_GET['city_name'];

    $url = 'https://api.openweathermap.org/data/2.5/weather?q='.$city_name.'&appid='.$APIkey;

    //Creo il CURL handle per l'URL selezionato
    $ch = curl_init($url);

    //Setto che voglio ritornato il valore, anziché un boolean (default)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //Eseguo la richiesta all'URL
    $data = curl_exec($ch);
    
    //Impacchetto tutto in un JSON
    $json = json_decode($data, true);
   
    //Libero le risorse
    curl_close($ch);

    echo json_encode($json);

 ?>






