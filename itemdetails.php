<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/23/2016
 * Time: 3:02 PM
 */?>
<?php

$pagetitle = "Movie Details";
include_once "header.php";

if(!isset($_SESSION['ID']))
{
    echo '<p>Please log in to view and use this page.  Thank you </p>';
    ?>  <img id="picture" src="images/error.jpg" alt="error" > <?php
    include_once "footer.php";
    exit();
}

try
{
    $sql = "SELECT * FROM dkfulp_items WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":ID", $_GET['ID']);
    $stmt->execute();
    $row = $stmt->fetch();

    echo "<table><tr><th>ID:</th><td>" . $row['ID'] . "</td></tr>";
    echo "<tr><th>Movie Title:</th><td>" . $row['title'] . "</td></tr>";
    echo "<tr><th>Description:</th><td>" . $row['description'] . "</td></tr>";
    echo "<tr><th>Year Released:</th><td>" . $row['year'] . "</td></tr>";
    echo "<tr><th>Input Date:</th><td>" . date("F j, Y",$row['inputdate']) . "</td></tr>";

    echo "</table>";
}
catch (PDOException $e)
{
    echo "Error fetching users: <br />ERROR MESSAGE:<br />" .$e->getMessage();
    exit();
}
?>
<img id="picture" src="images/dead.jpg" alt="dead" >
<?php
include_once "footer.php";
?>

