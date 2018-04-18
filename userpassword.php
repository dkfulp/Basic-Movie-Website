<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/20/2016
 * Time: 1:36 PM
 */
?>
<?php
$pagetitle = "User Password Update";
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


        /* ****************************************************************************
          SANITIZE DATA FROM USER.
        **************************************************************************** */
        $formfield['pwd'] = trim($_POST['pwd']); //we never force a lowercase password
        $formfield['pwd2'] = trim($_POST['pwd2']); //we never force a lowercase password

        /*  ****************************************************************************
           CHECK FOR EMPTY FIELDS
        **************************************************************************** */

        if(empty($formfield['pwd'])){$errormsg .= "<p>The password is empty.</p>";}
        if(empty($formfield['pwd2'])){$errormsg .= "<p>The confirmation password is empty.</p>";}


        /*  ****************************************************************************
          CHECK FOR MATCHING PASSWORDS
        **************************************************************************** */
        if($formfield['pwd'] != $formfield['pwd2']){$errormsg .= "<p>The passwords do not match.</p>";}


        /*  ****************************************************************************
        HASH THE PASSWORD
           **************************************************************************** */

        $alphabet = "./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for ($i = 0; $i < 22; $i++) {
            $chars .= substr($alphabet, mt_rand(0, 63), 1);
        }
        $salt = '$2a$10$' . $chars;
        $securepwd = crypt($formfield['pwd'],$salt);

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
                $sql = "UPDATE dkfulp_users SET
						pwd = :pwd, salt = :salt
						WHERE ID = :ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindvalue(':ID', $_SESSION['ID']);
                $stmt->bindvalue(':pwd', $securepwd);
                $stmt->bindvalue(':salt', $salt);
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
        ?>
        <form method="post" action="userpassword.php" name="myform">
            <fieldset>
                <legend>Update Form</legend>
                <table>
                    <tr>
                        <th><label for="pwd">Password:</label></th>
                        <td><input type="password" name="pwd" id="pwd" /></td>
                    </tr>
                    <tr>
                        <th><label for="pwd2">Confirm Password:</label></th>
                        <td><input type="password" name="pwd2" id="pwd2"></td>
                    </tr>
                    <tr>
                        <th>Submit:</th>
                        <td><input type="submit" name="submit" value="UPDATE"/></td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <img id="picture" src="images/pass.png" alt="pass" >
        <?php
    }
}//showform
include_once "footer.php";
?>