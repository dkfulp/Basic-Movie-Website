<?php
/**
 * Created by PhpStorm.
 * User: dakot_000
 * Date: 4/20/2016
 * Time: 12:57 PM
 */?>
<?php
$pagetitle = "Home Page";
require_once "header.php";
?>
<div id ="hometext">
<p>In this assignment, my objective was to make a website that would allow users to add, update, delete, and view an
assorted list of movies.  I also allow non members to view the content, but they cannot manipulate the data as well.
This website keeps track of member information and video information as well.</p>
</div>

<img id="picture" src="images/movie.PNG" alt="movie" >
<?php

require_once "items.php";

include_once "footer.php";
?>
