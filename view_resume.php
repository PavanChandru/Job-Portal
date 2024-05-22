<?php
session_start(); // Start the session
include('db.php'); // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Retrieve user's username from session
$username = $_SESSION['user'];

// Retrieve resume data from the database
$query = $conn->prepare("SELECT resume FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $resume_data = $row['resume'];

    // Check if resume data exists
    if ($resume_data !== null) {
        // Set the appropriate header for a PDF file
        header('Content-type: application/pdf');

        // Output the resume data
        echo $resume_data;
    } else {
        echo "Error: Resume not found.";
    }
} else {
    echo "Error: User not found.";
}
?>
