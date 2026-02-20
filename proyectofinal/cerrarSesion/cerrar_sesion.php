<?php
session_start();
session_destroy();
header("Location: ../pantallaseleccion/principal.php?cerrado=1");
