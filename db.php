<?php
$conn = mysqli_connect("localhost", "root", "", "mypokemon_catalogue");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
