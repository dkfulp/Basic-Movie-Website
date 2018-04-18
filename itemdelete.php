<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/23/2016
 * Time: 3:02 PM
 */?>
<?php

$pagetitle = "Delete Movie";
include_once "header.php";

if(!isset($_SESSION['ID']))
{
    echo '<p>Please log in to view and use this page.  Thank you </p>';
    ?>  <img id="picture" src="images/error.jpg" alt="error" > <?php
    include_once "footer.php";
    exit();
}

//NECESSARY VARIABLES
$showform = 1;

if(isset($_POST['delete']))
{
    try
    {
        $sql = 'DELETE FROM dkfulp_items WHERE ID = :ID';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $_POST['ID']);
        $stmt->execute();
        echo "<div class = 'success'><p>Successfully deleted.</p></div>";
        $showform = 0;
    }
    catch (PDOException $e)
    {
        echo "<div class ='error'><p>ERROR deleting data!" .$e->getMessage() . "</p></div>";
        include "footer.php";
        exit();
    }

}//if post delete

if($showform == 1)
{
    ?>
    <p> Would you really like to delete <?php $sql = 'SELECT title FROM dkfulp_items WHERE ID = :ID';
                                                        $stmt = $pdo->prepare($sql);
                                                        $stmt-> bindValue(':ID', $_GET['ID']);
                                                        $stmt->execute();
                                                        $row = $stmt->fetch();
                                                        echo $row['title']?>?.</p>

    <form name="delete" id="delete" method="post" action="itemdelete.php">
        <input type="hidden" name="ID" value="<?php echo $_GET['ID'];?>">
        <input type="submit" name="delete" value="YES">
        <input type="button" name="nodelete" value="NO" onclick="window.location='itemlist.php'" />
    </form>
    <img id="picture" src="images/sadface.png" alt="sadface" >
    <?php
}//end if showform
include_once "footer.php";
?>
