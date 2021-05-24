
<?php 
    /*Ritorna un JSON con i risultati dell'API selezionata*/
   
    $url = 'https://api.covid19api.com/summary';

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






