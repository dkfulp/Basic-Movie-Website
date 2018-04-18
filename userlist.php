<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/20/2016
 * Time: 1:35 PM
 */
?>
<?php
$pagetitle = "User Directory";
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
    $sql = "SELECT * FROM dkfulp_users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo "<table><tr><th>Options</th><th>First Name</th><th>Last Name</th><th>Username</th></tr>";
    foreach ($result as $row)
    {
        echo "<tr>
					<td><a href='userdetails.php?ID=" . $row['ID'] . "'>VIEW</a> | <a href='userupdate.php?ID=" . $row['ID'] . "'>UPDATE</a></td>
					<td>" . $row['fname'] . "</td>
			        <td>" . $row['lname'] . "</td>
			        <td>" . $row['uname'] . "</td>
			      </tr>";
    }
    echo "</table>";

}//try
catch (PDOException $e)
{
    echo 'Error fetching users: <br />ERROR MESSAGE:<br />' .$e->getMessage();
    exit();
}
?>
<img id="picture" src="images/directory.jpg" alt="directory" >
<?php
include_once "footer.php";
?>

