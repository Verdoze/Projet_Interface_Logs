<?php
require __DIR__.'/../inc/db.php';
require __DIR__.'/../inc/auth.php';
logout();
header('Location: /login.php');