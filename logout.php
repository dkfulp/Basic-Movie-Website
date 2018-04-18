<?php
session_start();
session_unset();
session_destroy();
if($_GET['state'] == 3)
{
    header("Location: confirm.php?state=3");
}
else
{
    header("Location: confirm.php?state=1");
}
exit()

/*notice this page has NO html, etc.... We end the session and immediately go back to  the index page.*/
?>