<?php
require('../php_require/session.php');
session_destroy();
echo '<script>window.location.href="/index.php";</script>';