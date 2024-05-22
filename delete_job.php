<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if company user is logged in
if (!isset($_SESSION['company_user'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Include database connection
include('db.php');

// Check if job ID is provided in the URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Prepare SQL statement to delete job from the database
    $query = $conn->prepare("DELETE FROM jobs WHERE job_id = ?");
    $query->bind_param("i", $job_id);

    // Execute SQL query
    if ($query->execute()) {
        // Redirect to posted jobs page after successful deletion
        header("Location: posted_job.php");
        exit();
    } else {
        echo "Error: Failed to delete job.";
    }
} else {
    // Job ID not provided, redirect back to posted jobs page
    header("Location: posted_job.php");
    exit();
}

// Close database connection
$conn->close();
?>
