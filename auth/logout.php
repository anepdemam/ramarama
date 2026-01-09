<?php
session_start();

if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>

<script>
if (confirm("Are you sure you want to logout?")) {
    window.location.href = "logout.php?confirm=yes";
} else {
    window.location.href = "index.php";
}
</script>
