<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/20/2016
 * Time: 1:36 PM
 */?>
<?php
$pagetitle = "User Details";
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
    $sql = "SELECT * FROM dkfulp_users WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":ID", $_GET['ID']);
    $stmt->execute();
    $row = $stmt->fetch();

    echo "<table><tr><th>ID:</th><td>" . $row['ID'] . "</td></tr>";
    echo "<tr><th>First Name:</th><td>" . $row['fname'] . "</td></tr>";
    echo "<tr><th>Middle Initial:</th><td>" . $row['mi'] . "</td></tr>";
    echo "<tr><th>Last Name:</th><td>" . $row['lname'] . "</td></tr>";
    echo "<tr><th>Email Address:</th><td>" . $row['email'] . "</td></tr>";
    echo "<tr><th>Username:</th><td>" . $row['uname'] . "</td></tr>";
    echo "<tr><th>Address Line 1:</th><td>" . $row['address1'] . "</td></tr>";
    echo "<tr><th>Address Line 2:</th><td>" . $row['address2'] . "</td></tr>";
    echo "<tr><th>City:</th><td>" . $row['city'] . "</td></tr>";
    echo "<tr><th>State:</th><td>" . $row['state'] . "</td></tr>";
    echo "<tr><th>ZipCode:</th><td>" . $row['zip'] . "</td></tr>";

    echo "</table>";
}
catch (PDOException $e)
{
    echo "Error fetching users: <br />ERROR MESSAGE:<br />" .$e->getMessage();
    exit();
}
?>
<img id="picture" src="images/taun.jpeg" alt="taun" >
<?php
include_once "footer.php";
?>
