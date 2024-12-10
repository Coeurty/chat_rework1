<?php
require_once '../php_require/session.php';
session_destroy();
header('location: /account/login.php');