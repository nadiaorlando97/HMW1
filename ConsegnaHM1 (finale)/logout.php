
<?php
session_start();

session_destroy(); 
header('Location: index.php?msg='.urlencode("Logout avvenuto con successo"));

exit;

?>