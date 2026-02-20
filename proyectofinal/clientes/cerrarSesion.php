<?php
session_start();
session_destroy();
header("Location: menuPlatos.php");
exit();
