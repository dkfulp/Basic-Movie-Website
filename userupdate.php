<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/20/2016
 * Time: 1:36 PM
 */?>
<?php
$pagetitle = "User Update Form";
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
else if($_SESSION['ID'] != $_GET['ID'] && $_SESSION['ID'] != $_POST['ID'])
{
        echo '<p>Invalid Acess.  Please log in as this user to change account details </p>';
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
        $formfield['fname'] = trim($_POST['fname']);
        $formfield['mi'] = trim($_POST['mi']);
        $formfield['lname'] = trim($_POST['lname']);
        $formfield['email'] = trim(strtolower($_POST['email']));
        $formfield['uname'] = trim(strtolower($_POST['uname']));
        $formfield['address1'] = trim($_POST['address1']);
        $formfield['address2'] = trim($_POST['address2']);
        $formfield['city'] = trim($_POST['city']);
        $formfield['state'] = trim($_POST['state']);
        $formfield['zip'] = trim($_POST['zip']);

        /*  ****************************************************************************
           CHECK FOR EMPTY FIELDS
        **************************************************************************** */

        if(empty($formfield['fname'])){$errormsg .= "<p>The first nam is empty.</p>";}
        if(empty($formfield['lname'])){$errormsg .= "<p>The last name is empty.</p>";}
        if(empty($formfield['email'])){$errormsg .= "<p>The email is empty.</p>";}
        if(empty($formfield['uname'])){$errormsg .= "<p>The username is empty.</p>";}
        if(empty($formfield['address1'])){$errormsg .= "<p>The primary address field is empty.</p>";}
        if(empty($formfield['city'])){$errormsg .= "<p>There is no city.</p>";}
        if(empty($formfield['state'])){$errormsg .= "<p>No state was never declared.</p>";}
        if(empty($formfield['zip'])){$errormsg .= "<p>No zipcode was entered.</p>";}

        /* ****************************************************************************
              CHECK FOR DUPLICATE USERS
           ************************************************************************* */
        if($formfield['uname'] != $_POST['ouname'])
        {
            try {

                $sqlusers = "SELECT * FROM dkfulp_users WHERE uname = :uname";
                $stmtusers = $pdo->prepare($sqlusers);
                $stmtusers->bindValue(':uname', $formfield['uname']);
                $stmtusers->execute();
                $countusers = $stmtusers->rowCount();
                if ($countusers > 0) {
                    $errormsg .= "<p>The username is already taken.</p>";
                }
            }
            catch (PDOException $e) {
                echo "<div class='error'><p></p>ERROR selecting users!" . $e->getMessage() . "</p></div>";
                exit();
            }
        }

        /* ****************************************************************************
                      CHECK FOR DUPLICATE EMAILS
           ************************************************************************* */
        if($formfield['email'] != $_POST['oemail'])
        {
            try {

                $sqlemail = "SELECT * FROM dkfulp_users WHERE email = :email";
                $sqlemail = $pdo->prepare($sqlemail);
                $sqlemail->bindValue(':email', $formfield['email']);
                $sqlemail->execute();
                $countemails = $sqlemail->rowCount();
                if ($countemails > 0) {
                    $errormsg .= "<p>The email is already taken.</p>";
                }
            } catch (PDOException $e) {
                echo "<div class='error'><p></p>ERROR selecting users!" . $e->getMessage() . "</p></div>";
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
                $sql = "UPDATE dkfulp_users SET
						fname = :fname, mi = :mi, lname = :lname, email = :email, uname = :uname, address1 = :address1, address2 = :address2,
						 city = :city, state = :state, zip = :zip
						WHERE ID = :ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindvalue(':ID', $_POST['ID']);
                $stmt->bindvalue(':fname', $formfield['fname']);
                $stmt->bindvalue(':mi', $formfield['mi']);
                $stmt->bindvalue(':lname', $formfield['lname']);
                $stmt->bindvalue(':email', $formfield['email']);
                $stmt->bindvalue(':uname', $formfield['uname']);
                $stmt->bindvalue(':address1', $formfield['address1']);
                $stmt->bindvalue(':address2', $formfield['address2']);
                $stmt->bindvalue(':city', $formfield['city']);
                $stmt->bindvalue(':state', $formfield['state']);
                $stmt->bindvalue(':zip', $formfield['zip']);
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
        $sql = 'SELECT * FROM dkfulp_users WHERE ID = :ID';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $_GET['ID']);
        $stmt->execute();
        $row = $stmt->fetch();
        ?>
        <form method="post" action="userupdate.php" name="myform">
            <fieldset>
                <legend>Update Form</legend>
                <table>
                    <tr>
                        <th><label for="fname">User Name:</label></th>
                        <td><input type="text" name="fname" id="textbox" value="<?php if (isset($formfield['fname'])
                                && !empty($formfield['fname'])
                            ) {
                                echo $formfield['fname'];
                            } else {
                                echo $row['fname'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="mi">Middle Initial:</label></th>
                        <td><input type="text" name="mi" id="textbox" value="<?php if (isset($formfield['mi'])
                                && !empty($formfield['mi'])
                            ) {
                                echo $formfield['mi'];
                            } else {
                                echo $row['mi'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="lname">Last Name:</label></th>
                        <td><input type="text" name="lname" id="textbox" value="<?php if (isset($formfield['lname'])
                                && !empty($formfield['lname'])
                            ) {
                                echo $formfield['lname'];
                            } else {
                                echo $row['lname'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="email">Email:</label></th>
                        <td><input type="email" name="email" id="email" value="<?php if (isset($formfield['email'])
                                && !empty($formfield['email'])
                            ) {
                                echo $formfield['email'];
                            } else {
                                echo $row['email'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="uname">Username:</label></th>
                        <td><input type="text" name="uname" id="textbox" value="<?php if (isset($formfield['uname'])
                                && !empty($formfield['uname'])
                            ) {
                                echo $formfield['uname'];
                            } else {
                                echo $row['uname'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="address1">Address Line 1:</label></th>
                        <td><input type="text" name="address1" id="textbox" value="<?php if (isset($formfield['address1'])
                                && !empty($formfield['address1'])
                            ) {
                                echo $formfield['address1'];
                            } else {
                                echo $row['address1'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="address2">Address Line 2:</label></th>
                        <td><input type="text" name="address2" id="textbox" value="<?php if (isset($formfield['address2'])
                                && !empty($formfield['address2'])
                            ) {
                                echo $formfield['address2'];
                            } else {
                                echo $row['address2'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="city">City:</label></th>
                        <td><input type="text" name="city" id="textbox" value="<?php if (isset($formfield['city'])
                                && !empty($formfield['city'])
                            ) {
                                echo $formfield['city'];
                            } else {
                                echo $row['city'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="state">State:</label></th>
                        <td><input type="text" name="state" id="textbox" value="<?php if (isset($formfield['state'])
                                && !empty($formfield['state'])
                            ) {
                                echo $formfield['state'];
                            } else {
                                echo $row['state'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th><label for="zip">User Name:</label></th>
                        <td><input type="text" name="zip" id="textbox" value="<?php if (isset($formfield['zip'])
                                && !empty($formfield['zip'])
                            ) {
                                echo $formfield['zip'];
                            } else {
                                echo $row['zip'];
                            } ?>"/></td>
                    </tr>
                    <tr>
                        <th>Submit:</th>
                        <td><input type="hidden" name="ID" id="ID" value="<?php echo $row['ID']; ?>"/>
                            <input type="hidden" name="ouname" id="ouname" value="<?php echo $row['uname']; ?>"/>
                            <input type="hidden" name="oemail" id="oemail" value="<?php echo $row['email']; ?>"/>
                            <input type="submit" name="submit" value="UPDATE"/></td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <img id="picture" src="images/shifting.png" alt="shifting" >
        <?php
    }
}//showform
include_once "footer.php";
?>
