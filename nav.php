<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/20/2016
 * Time: 9:34 AM
 */?>
<?php
?>
<ul>
    <?php
    $currentfile = basename($_SERVER['PHP_SELF']);
    echo ($currentfile == "index.php") ? "<li>Home</li>" : "<li><a href='index.php'>Home</a></li>";
    echo ($currentfile == "useradd.php") ? "<li>Add User</li>" : "<li><a href='useradd.php'>Add User</a></li>";
    echo (isset($_SESSION['ID'])) ? "<li><a href='userlist.php'>User Directory</a></li>" : "<li><a href='userlist.php'>User Directory</a></li>";
    echo (isset($_SESSION['ID'])) ? "<li><a href='itemadd.php'>Add a Movie</a></li>" : "<li><a href='itemadd.php'>Add a Movie</a></li>";
    echo (isset($_SESSION['ID'])) ? "<li><a href='itemlist.php'>Movie Directory</a></li>" : "<li><a href='itemlist.php'>Movie Directory</a></li>";
    echo (isset($_SESSION['ID'])) ? "<li><a href='userpassword.php'>Update User Password</a></li>" : "<li></li>";
    echo (isset($_SESSION['ID'])) ? "<li><a href='logout.php'>Log Out</a></li>" : "<li><a href='login.php'>Log In</a></li>";
    ?>
</ul>
