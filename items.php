<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/23/2016
 * Time: 3:01 PM
 */?>
<?php

$pagetitle = "Movies";

try
{
    $sql = "SELECT * FROM dkfulp_items";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo "<table><tr><th>Movies: Click to see more info</th></tr>";
    foreach ($result as $row)
    {
        echo "<tr>
					<td><a href='itemdetails.php?ID=" . $row['ID'] . "'>$row[title]</a></td>

			      </tr>";
    }
    echo "</table>";

}//try
catch (PDOException $e)
{
    echo 'Error fetching items: <br />ERROR MESSAGE:<br />' .$e->getMessage();
    exit();
}
