<?php
session_start();
session_unset();
session_destroy();

header("Location: admin_dashboard.php?logout=sukses");
exit;
?>