<?php
// Start session
session_start();

// Check if user is admin
if (!isset($_SESSION['admin'])) {
    // Redirect to login page if user is not admin
    header("Location: login.php");
    exit();
}

// Include database connection
include('db.php');

// Check if company_id is provided
if (isset($_GET['company_id'])) {
    $company_id = $_GET['company_id'];

    // Delete company from the database
    $delete_query = $conn->prepare("DELETE FROM companies WHERE company_id = ?");
    $delete_query->bind_param("i", $company_id);
    $delete_query->execute();

    // Redirect back to manage_user.php after deletion
    header("Location: manage_user.php");
    exit();
} else {
    // If company_id is not provided, redirect back to manage_user.php
    header("Location: manage_user.php");
    exit();
}
?>
