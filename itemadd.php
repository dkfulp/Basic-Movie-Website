<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/23/2016
 * Time: 3:02 PM
 */?>
<?php

$pagetitle = "Movie Entry Form";
include_once "header.php";

if(!isset($_SESSION['ID']))
{
    echo '<p>Please log in to view and use this page.  Thank you </p>';
    ?>  <img id="picture" src="images/error.jpg" alt="error" > <?php
    include_once "footer.php";
    exit();
}

$errormsg = "";
$showform = 1;

if(isset($_POST['submit']))
{
    /* ****************************************************************************
       CREATE NEW VARS TO STORE USER DATA & SANITIZE DATA FROM USER.
       ************************************************************************* */
    $formfield['title'] = trim(strtoupper($_POST['title']));
    $formfield['description'] = trim($_POST['description']);
    $formfield['year'] = trim($_POST['year']);
    $formfield['inputdate'] = time();
    /* ****************************************************************************
        CHECK FOR EMPTY FIELDS
       ************************************************************************* */
    if(empty($formfield['title'])){$errormsg .= "<p>The Title is empty.</p>";}
    if(empty($formfield['description'])){$errormsg .= "<p>The Description is empty.</p>";}
    if(empty($formfield['year'])){$errormsg .= "<p>The year is empty.</p>";}


    /* ****************************************************************************
          CHECK FOR DUPLICATE TITLES
       ************************************************************************* */
    try
    {
        $sqltitle = "SELECT * FROM dkfulp_items WHERE title = :title";
        $stmttitle = $pdo->prepare($sqltitle);
        $stmttitle->bindValue(':title', $formfield['title']);
        $stmttitle->execute();
        $counttitle = $stmttitle->rowCount();
        if ($counttitle > 0)
        {
            $errormsg .= "<p>The title is already taken.</p>";
        }
    }
    catch (PDOException $e)
    {
        echo "<div class='error'><p></p>ERROR selecting users!" .$e->getMessage() . "</p></div>";
        exit();
    }


    /*  ****************************************************************************
        CONTROL FOR ERRORS.  IF ERRORS, DISPLAY THEM.  IF NOT, CONTINUE WITH FORM PROCESSING.
        **************************************************************************** */
    if($errormsg != "")
    {
        echo "<div class='error'><p>THERE ARE ERRORS!</p>";
        echo $errormsg;
        echo "</div>";
    }
    else
    {
        try
        {
            $sql = "INSERT INTO dkfulp_items (title, description, year, inputdate)
								   VALUES (:title, :description, :year, :inputdate)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindvalue(':title', $formfield['title']);
            $stmt->bindvalue(':description', $formfield['description']);
            $stmt->bindvalue(':year', $formfield['year']);
            $stmt->bindvalue(':inputdate', $formfield['inputdate']);
            $stmt->execute();
            $showform=0; //hide the form
            echo "<div class='success'><p>There are no errors.  Thank you.  Go to <a href='itemlist.php'>Item Directory</a></p></div>";
        }//try
        catch(PDOException $e)
        {
            echo "<div class='error'><p>ERROR inserting data into the database!" .$e->getMessage() . "</p></div>";
            exit();
        }
    }//else errors
}//isset submit

if($showform == 1) {
    ?>
    <form method="post" action="itemadd.php" name="registration">
        <fieldset>
            <legend>Movie Registration</legend>
            <table>
                <tr>
                    <th><label for="title">Movie Title:</label></th>
                    <td><input type="text" name="title" id="title" value="<?php if (isset($formfield['title'])){echo $formfield['title'];} ?>"/></td>
                </tr>
                <tr>
                    <th><label for="description">Description:</label></th>
                    <td><textarea name="description" id="description"><?php if (isset($formfield['description'])){echo $formfield['description'];} ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="year">Year:</label></th>
                    <td><input type="number" name="year" id="year" maxlength="4" value="<?php if (isset($formfield['year'])){echo $formfield['year'];} ?>"/></td>
                </tr>
                <tr>
                    <th>Submit:</th>
                    <td><input type="submit" name="submit" value="submit"/></td>
                </tr>
            </table>
        </fieldset>
    </form>
    <img id="picture" src="images/rell.jpg" alt="rell" >
    <?php
} //showform
include_once "footer.php";
?>
