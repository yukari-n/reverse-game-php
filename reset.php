<?php
print_r($_SESSION);
$_SESSION = array();
session_destroy();

echo '<p>Data was deleted.</p>';

print_r($_SESSION);

echo '<p><a href="/">Back</a></p>';