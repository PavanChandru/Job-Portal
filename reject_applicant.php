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

// Check if job ID and user ID are provided in the URL
if (isset($_GET['job_id']) && isset($_GET['user_id'])) {
    $job_id = $_GET['job_id'];
    $user_id = $_GET['user_id'];

    // Check if the applicant is already rejected
    $check_query = $conn->prepare("SELECT application_status FROM applicants WHERE job_id = ? AND user_id = ?");
    $check_query->bind_param("ii", $job_id, $user_id);
    $check_query->execute();
    $check_result = $check_query->get_result();

    if ($check_result->num_rows == 1) {
        $row = $check_result->fetch_assoc();

        // If the applicant is rejected, change status to 'reviewed'
        if ($row['application_status'] == 'rejected') {
            $update_query = $conn->prepare("UPDATE applicants SET application_status = 'reviewed' WHERE job_id = ? AND user_id = ?");
            $update_query->bind_param("ii", $job_id, $user_id);
            $update_query->execute();
        } else {
            // If the applicant is not rejected, change status to 'rejected'
            $update_query = $conn->prepare("UPDATE applicants SET application_status = 'rejected' WHERE job_id = ? AND user_id = ?");
            $update_query->bind_param("ii", $job_id, $user_id);
            $update_query->execute();
        }
    }
}

// Redirect back to applicants page
header("Location: applicants.php?job_id=$job_id");
exit();
?>
