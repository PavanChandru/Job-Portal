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

// Retrieve company ID
$company_id = $_SESSION['company_id'];

// Fetch jobs posted by the company user
$query = $conn->prepare("SELECT * FROM jobs WHERE company_id = ?");
$query->bind_param("i", $company_id);
$query->execute();
$result = $query->get_result();

// Calculate container height based on the number of jobs
$baseHeight = 100; // Base height for the container padding and margins
$jobHeight = 215; // Height per job
$containerHeight = $baseHeight + ($result->num_rows * $jobHeight);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posted Jobs</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Include your CSS file -->
    <style>
        .container {
            margin: 20px auto;
            max-width: 800px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            min-height: <?= $containerHeight ?>px; /* Apply the calculated height */
        }

        .job {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .job h3 {
            margin-top: 0;
            font-size: 20px;
        }

        .job p {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .edit-job,
        .delete-job,
        .view-applicants {
            display: inline-block;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            margin-right: 10px;
        }

        .edit-job:hover,
        .delete-job:hover,
        .view-applicants:hover {
            background-color: #0056b3;
        }

        .delete-job {
            background-color: #dc3545;
        }

        .delete-job:hover {
            background-color: #bd2130;
        }

        .view-applicants {
            background-color: #28a745;
        }

        .view-applicants:hover {
            background-color: #218838;
        }
        header {
            margin-bottom: 30px;
        }
        footer {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <div class="container">
        <h2>Posted Jobs</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="job">';
                echo '<h3>' . $row['title'] . '</h3>';
                echo '<p><strong>Description:</strong> ' . $row['description'] . '</p>';
                echo '<p><strong>Salary:</strong> $' . $row['salary'] . '</p>';
                echo '<a href="edit_job.php?job_id=' . $row['job_id'] . '" class="edit-job">Edit Job</a>';
                echo '<a href="delete_job.php?job_id=' . $row['job_id'] . '" class="delete-job" onclick="return confirm(\'Are you sure you want to delete this job?\')">Delete Job</a>';
                echo '<a href="applicants.php?job_id=' . $row['job_id'] . '" class="view-applicants">View Applicants</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No jobs posted yet.</p>';
        }
        ?>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>
