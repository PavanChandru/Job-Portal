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

    // Fetch job details from the database
    $query = $conn->prepare("SELECT * FROM jobs WHERE job_id = ?");
    $query->bind_param("i", $job_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        // Job not found, redirect back to posted jobs page
        header("Location: posted_job.php");
        exit();
    }
} else {
    // Job ID not provided, redirect back to posted jobs page
    header("Location: posted_job.php");
    exit();
}

// Fetch applicants for the job
$applicants_query = $conn->prepare("SELECT u.user_id, u.email, u.f_name, u.l_name, u.phone, a.application_status, a.application_date 
                                    FROM applicants a 
                                    INNER JOIN users u ON a.user_id = u.user_id 
                                    WHERE a.job_id = ?");
$applicants_query->bind_param("i", $job_id);
$applicants_query->execute();
$applicants_result = $applicants_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants for Job</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Include your CSS file -->
    <style>
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            min-height: 600px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button-container {
            margin-bottom: 20px;
        }
        .button-container a {
            display: inline-block;
            margin-right: 10px;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .button-container a:hover {
            background-color: #0056b3;
        }
        .viewed {
            color: green;
        }
        .reject-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 10px;
        }
        .reject-btn:hover {
            background-color: #bd2130;
        }
        .withdraw-btn {
            background-color: #ffc107;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 10px;
        }
        .withdraw-btn:hover {
            background-color: #e0a800;
        }
        .profile-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .profile-btn:hover {
            background-color: #218838;
        }
        header {
            margin-bottom: 20px;
        }
        footer {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?> <!-- Include header file -->

    <div class="container">
        <div class="button-container">
            <a href="posted_job.php">Back to Posted Jobs</a>
        </div>
        <h2>Applicants for Job: <?php echo $row['title']; ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Application Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($applicant = $applicants_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $applicant['f_name'] . " " . $applicant['l_name'] . "</td>";
                    echo "<td>" . $applicant['email'] . "</td>";
                    echo "<td>" . $applicant['phone'] . "</td>";
                    echo "<td>" . $applicant['application_status'] . "</td>";
                    echo "<td>" . $applicant['application_date'] . "</td>";
                    echo "<td>";
                    echo "<a href='view_profile.php?user_id={$applicant['user_id']}' class='profile-btn'>View Profile</a>";
                    if ($applicant['application_status'] != 'rejected') {
                        echo "<a href='reject_applicant.php?job_id={$job_id}&user_id={$applicant['user_id']}' class='reject-btn'>Reject</a>";
                    } else {
                        echo "<a href='reject_applicant.php?job_id={$job_id}&user_id={$applicant['user_id']}' class='withdraw-btn'>Withdraw Reject</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include('footer.php'); ?> <!-- Include footer file -->
</body>
</html>
