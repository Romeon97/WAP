<?php
session_start();
session_destroy();
header("Location: index.blade.php");
exit();
