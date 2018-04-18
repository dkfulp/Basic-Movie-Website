<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/20/2016
 * Time: 9:29 AM
 */?>
<?php
session_start();
require_once "connect.php";
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <title>Dakota Fulp</title>
    <link rel="stylesheet" href="styles.css" />
    <!--add TinyMCE on next line -->
    <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
    <script>
        tinymce.init({
            selector: 'textarea'
        });
    </script>
</head>
<body>
<header>
    <h1>Dakota's Movie DataBase</h1>
    <nav>
        <?php require_once "nav.php"; ?>
    </nav>
</header>
<h2><?php echo $pagetitle; ?></h2>