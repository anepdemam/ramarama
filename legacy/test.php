<?php
session_start();
echo 'User Role: ' . ($_SESSION['user_role'] ?? 'not set');
