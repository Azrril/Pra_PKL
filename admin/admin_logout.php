<?php
session_start();
session_destroy();

header("Location: admin_dashboard.php?logout=sukses");
exit;
?>