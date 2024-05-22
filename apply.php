<?php
// Include header
include('header.php');

// Include database connection
include('db.php');

// Check if job_id is set in the URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    // Check if the user is logged in
    if (isset($_SESSION['user'])) {
        // Get user_id from session
        $user_id = $_SESSION['user_id'];
        // Check if the user has already applied for this job
        $check_query = $conn->prepare("SELECT * FROM applicants WHERE job_id = ? AND user_id = ?");
        $check_query->bind_param("ii", $job_id, $user_id);
        $check_query->execute();
        $check_result = $check_query->get_result();
        if ($check_result->num_rows > 0) {
            // User has already applied for this job
            echo "<script>alert('You have already applied for this job.'); window.location.href = 'search.php';</script>";
            exit; // Stop further execution
        } else {
            // Insert application into applicants table
            $query = $conn->prepare("INSERT INTO applicants (job_id, user_id) VALUES (?, ?)");
            $query->bind_param("ii", $job_id, $user_id);
            if ($query->execute()) {
                // Application submitted successfully
                echo "<script>alert('Application submitted successfully!'); window.location.href = 'search.php';</script>";
                exit; // Stop further execution
            } else {
                // Failed to submit application
                echo "<p>Failed to submit application.</p>";
            }
        }
    } else {
        // Prompt to log in
        echo '<div class="login-prompt center">';
        echo 'Please log in to apply for a job.';
        echo '</div>';
        echo '<div class="center">';
        echo '<a href="login.php" class="login-button">Log In</a>';
        echo '</div>';
    }
} else {
    echo "<p>No job selected.</p>";
}

// Include footer
include('footer.php');
?>
