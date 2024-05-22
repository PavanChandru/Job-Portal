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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $company_id = $_SESSION['company_id'];
    $company_name = $_POST['company_name'];
    $industry = $_POST['industry'];
    $size = $_POST['size'];

    // Validate 'size' field to ensure it matches one of the ENUM options
    $allowed_sizes = ['Small', 'Medium', 'Large'];
    if (!in_array($size, $allowed_sizes)) {
        // Alert error message and redirect back to profile page
        echo "<script>alert('Invalid size value. Please select from Small, Medium, or Large.');</script>";
        header("Location: profile_comp.php");
        exit();
    }

    $description = $_POST['description'];
    $address = $_POST['address'];

    // Prepare SQL statement to update company information in the database
    $query = $conn->prepare("UPDATE companies SET company_name = ?, industry = ?, size = ?, description = ?, address = ? WHERE company_id = ?");
    $query->bind_param("sssssi", $company_name, $industry, $size, $description, $address, $company_id);

    // Execute SQL query
    if ($query->execute()) {
        // Alert success message
        echo "<script>alert('Company information updated successfully!'); window.location.href='profile_comp.php';</script>";
        exit();
    } else {
        // Alert error message
        echo "<script>alert('Failed to update company information.');</script>";
    }
}

// Close database connection
$conn->close();
?>
