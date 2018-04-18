<?php
/* CREATE A CONNECTION TO THE SERVER */
$dsn = 'mysql:host=localhost;dbname=sp16csci20302';
$user = 'sp16csci20302';
$pwd = 'sp16csci20302!';
try{
    $pdo = new PDO($dsn,$user,$pwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo "ERROR connecting to database!" . $e->getMessage();
    exit();
}
?>
