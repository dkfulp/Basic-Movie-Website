<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/23/2016
 * Time: 3:01 PM
 */?>
<?php

$pagetitle = "Movie Directory";
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
    $sql = "SELECT * FROM dkfulp_items";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo "<table><tr><th>Options</th><th>Movie Title</th><th>Year Released</th></tr>";
    foreach ($result as $row)
    {
        echo "<tr>
					<td><a href='itemdetails.php?ID=" . $row['ID'] . "'>DETAILS</a> | <a href='itemupdate.php?ID=" . $row['ID'] . "'>UPDATE</a> | <a href='itemdelete.php?ID=" . $row['ID'] . "'>DELETE</a></td>
					<td>" . $row['title'] . "</td>
			        <td>" . $row['year'] . "</td>
			      </tr>";
    }
    echo "</table>";

}//try
catch (PDOException $e)
{
    echo 'Error fetching items: <br />ERROR MESSAGE:<br />' .$e->getMessage();
    exit();
}?>
<img id="picture" src="images/lots.jpg" alt="lots" >
<?php
include_once "footer.php";
?>
