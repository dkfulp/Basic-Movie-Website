<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/23/2016
 * Time: 3:02 PM
 */?>
<?php

$pagetitle = "Movie Information Update Form";
include_once "header.php";

$errormsg = "";
$showform = 1;

if(!isset($_SESSION['ID']))
{
    echo '<p>Please log in to view and use this page.  Thank you </p>';
    ?>  <img id="picture" src="images/error.jpg" alt="error" > <?php
    include_once "footer.php";
    exit();
}
else {

    if(isset($_POST['submit']))
    {
        $_GET['ID'] = $_POST['ID'];
        /* ****************************************************************************
          SANITIZE DATA FROM USER.
        **************************************************************************** */

        $formfield['title'] = trim(strtoupper($_POST['title']));
        $formfield['description'] = trim($_POST['description']);
        $formfield['year'] = trim($_POST['year']);
        $formfield['inputdate'] = time();

        /*  ****************************************************************************
           CHECK FOR EMPTY FIELDS
        **************************************************************************** */

        if(empty($formfield['title'])){$errormsg .= "<p>The Title is empty.</p>";}
        if(empty($formfield['description'])){$errormsg .= "<p>The Description is empty.</p>";}
        if(empty($formfield['year'])){$errormsg .= "<p>The year is empty.</p>";}

        /* ****************************************************************************
              CHECK FOR DUPLICATE USERS
           ************************************************************************* */
        if($formfield['title'] != $_POST['otitle'])
        {
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
        }

        /*  ****************************************************************************
            CONTROL FOR ERRORS.  IF ERRORS, DISPLAY THEM.  IF NOT, CONTINUE WITH FORM PROCESSING.
            **************************************************************************** */
        if($errormsg != "")
        {
            echo "<div class='error'><p>THERE ARE ERRORS! CHECK YOUR ENTRIES AND SUBMIT AGAIN.</p>";
            echo $errormsg;
            echo "</div>";
        }
        else
        {
            try
            {
                /* ****************************************************************************
                   UPDATE DATA IN THE DATABASE
                   **************************************************************************** */
                $sql = "UPDATE dkfulp_items SET
						title = :title, description = :description, year = :year
						WHERE ID = :ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindvalue(':ID', $_POST['ID']);
                $stmt->bindvalue(':title', $formfield['title']);
                $stmt->bindvalue(':description', $formfield['description']);
                $stmt->bindvalue(':year', $formfield['year']);
                $stmt->execute();
                //hide the form
                $showform=0;
                echo "<div class='success'><p>There are no errors.  Thank you.</p></div>";

            }//try
            catch(PDOException $e)
            {
                echo "<div class='error'><p></p>ERROR updating data into the database!" .$e->getMessage() . "</p></div>";
                exit();
            }
        }//else errors
    }//isset submit

    if ($showform == 1) {
        $sql = 'SELECT * FROM dkfulp_items WHERE ID = :ID';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $_GET['ID']);
        $stmt->execute();
        $row = $stmt->fetch();
        ?>
        <form method="post" action="itemupdate.php" name="myform">
            <fieldset>
                <legend>Movie Information Update Form</legend>
                <table>
                    <tr>
                        <th><label for="title">Movie Title:</label></th>
                        <td><input type="text" name="title" id="textbox" value="<?php if (isset($formfield['title'])
                                && !empty($formfield['title'])
                            ) {
                                echo $formfield['title'];
                            } else {
                                echo $row['title'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="description">Description:</label></th>
                        <td><textarea name="description" id="textbox"><?php if(isset($formfield['description'])&& !empty($formfield['uname'])){echo $formfield['description'];}else {echo $row['description'];}?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="year">Year Released:</label></th>
                        <td><input type="text" name="year" id="textbox" value="<?php if (isset($formfield['year'])
                                && !empty($formfield['year'])
                            ) {
                                echo $formfield['year'];
                            } else {
                                echo $row['year'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th>Submit:</th>
                        <td><input type="hidden" name="ID" id="ID" value="<?php echo $row['ID']; ?>"/>
                            <input type="hidden" name="otitle" id="otitle" value="<?php echo $row['title']; ?>"/>
                            <input type="submit" name="submit" value="UPDATE"/></td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <img id="picture" src="images/changing.jpg" alt="changing" >
        <?php
    }
}//showform
include_once "footer.php";
?>
