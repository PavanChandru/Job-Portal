<?php
session_start(); // Start the session
include('db.php'); // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user's username from session
    $username = $_SESSION['user'];

    // Retrieve form data
    $f_name = !empty($_POST['f_name']) ? $_POST['f_name'] : null;
    $l_name = !empty($_POST['l_name']) ? $_POST['l_name'] : null;
    $date_of_birth = !empty($_POST['date_of_birth']) ? date('Y-m-d', strtotime($_POST['date_of_birth'])) : null; // Format date properly
    $phone = !empty($_POST['phone']) ? $_POST['phone'] : null;
    $gender = !empty($_POST['gender']) && in_array($_POST['gender'], array('M', 'F', 'Other')) ? $_POST['gender'] : null; // Check if gender is set and valid
    $address = !empty($_POST['address']) ? $_POST['address'] : null;
    $resume_path = null; // Initialize resume path

    // Check if resume was uploaded
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $resume_tmp_name = $_FILES['resume']['tmp_name'];
        $resume_size = $_FILES['resume']['size'];
        $resume_type = $_FILES['resume']['type'];

        // Check file size
        if ($resume_size > 5000000) { // 5MB
            echo "Error: File size exceeds maximum limit.";
            exit();
        }

        // Check file type
        $allowed_types = array('pdf');
        $file_parts = pathinfo($_FILES['resume']['name']);
        $file_extension = strtolower($file_parts['extension']);

        if (!in_array($file_extension, $allowed_types)) {
            echo "Error: Only PDF files are allowed.";
            exit();
        }

        // Read file contents
        $resume_data = file_get_contents($resume_tmp_name);

        // Update resume path
        $resume_path = $resume_data; // You need to insert the resume data into the database directly
    }

    // Update user information
    $query = $conn->prepare("UPDATE users SET f_name = ?, l_name = ?, date_of_birth = ?, phone = ?, gender = ?, address = ?, resume = ? WHERE username = ?");
    $query->bind_param("ssssssss", $f_name, $l_name, $date_of_birth, $phone, $gender, $address, $resume_path, $username);

    if ($query->execute()) {
        echo "<script>alert('User information updated successfully.'); window.location.href='profile.php';</script>";
        exit();
    } else {
        echo "Error: Failed to update user information in database.";
        exit();
    }
} else {
    echo "Error: Invalid request method.";
    exit();
}
?>
