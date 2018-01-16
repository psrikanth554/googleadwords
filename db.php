<?php

$link = mysqli_connect('localhost', 'root', '');
if (!$link) {
    die('Not connected : ' . mysqli_error());
}

// make foo the current db
$db_selected = mysqli_select_db($link,'adwords');
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysqli_error());
}
?>
