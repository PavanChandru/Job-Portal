<?php
// Include header
include('header.php');

// Include database connection
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get user ID
$user_id = $_SESSION['user_id'];

// Function to retrieve saved jobs based on application status
function getSavedJobs($conn, $user_id, $status)
{
    $sql = "SELECT jobs.*, companies.company_name, applicants.application_date 
            FROM jobs 
            INNER JOIN applicants ON jobs.job_id = applicants.job_id 
            INNER JOIN companies ON jobs.company_id = companies.company_id 
            WHERE applicants.user_id = $user_id AND applicants.application_status = '$status' 
            ORDER BY applicants.application_date DESC";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Retrieve saved jobs for each status
$pending_jobs = getSavedJobs($conn, $user_id, 'pending');
$reviewed_jobs = getSavedJobs($conn, $user_id, 'reviewed');
$rejected_jobs = getSavedJobs($conn, $user_id, 'rejected');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Add your custom CSS styles for savedjob.php */
        /* Card styles */
        .card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        /* Tab styles */
        .tab {
            overflow: hidden;
            margin: 40px 0; /* Added margin top and bottom */
        }

        .tab button {
            background-color: inherit;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 10px 20px;
            transition: 0.3s;
            border-radius: 8px 8px 0 0;
        }

        .tab button.active {
            background-color: #007bff;
            color: #fff;
        }

        /* Card container styles */
        .card-container {
            margin-top: 20px;
        }

        /* No data container styles */
        .no-data {
            margin-top: 20px;
            text-align: center;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <main>
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'pending')">Pending</button>
            <button class="tablinks" onclick="openTab(event, 'reviewed')">Reviewed</button>
            <button class="tablinks" onclick="openTab(event, 'rejected')">Rejected</button>
        </div>

        <div id="pending" class="tabcontent" style="display: block;">
            <?php if (mysqli_num_rows($pending_jobs) > 0) : ?>
                <div class="card-container">
                    <?php while ($row = mysqli_fetch_assoc($pending_jobs)) : ?>
                        <div class="card">
                            <h3><?= $row['title'] ?></h3>
                            <p><strong>Company:</strong> <?= $row['company_name'] ?></p>
                            <p><strong>Date Applied:</strong> <?= $row['application_date'] ?></p>
                            <p><strong>Status:</strong> Pending</p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <div class="no-data">No pending applications found.</div>
            <?php endif; ?>
        </div>

        <div id="reviewed" class="tabcontent" style="display: none;">
            <?php if (mysqli_num_rows($reviewed_jobs) > 0) : ?>
                <div class="card-container">
                    <?php while ($row = mysqli_fetch_assoc($reviewed_jobs)) : ?>
                        <div class="card">
                            <h3><?= $row['title'] ?></h3>
                            <p><strong>Company:</strong> <?= $row['company_name'] ?></p>
                            <p><strong>Date Applied:</strong> <?= $row['application_date'] ?></p>
                            <p><strong>Status:</strong> Reviewed</p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <div class="no-data">No reviewed applications found.</div>
            <?php endif; ?>
        </div>

        <div id="rejected" class="tabcontent" style="display: none;">
            <?php if (mysqli_num_rows($rejected_jobs) > 0) : ?>
                <div class="card-container">
                    <?php while ($row = mysqli_fetch_assoc($rejected_jobs)) : ?>
                        <div class="card">
                            <h3><?= $row['title'] ?></h3>
                            <p><strong>Company:</strong> <?= $row['company_name'] ?></p>
                            <p><strong>Date Applied:</strong> <?= $row['application_date'] ?></p>
                            <p><strong>Status:</strong> Rejected</p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <div class="no-data">No rejected applications found.</div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>

    <?php
    // Include footer
    include('footer.php');
    ?>
</body>
</html>
