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

// Get user ID from the URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user details from the database
    $query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Fetch user's skills from the database
        $skills_query = $conn->prepare("SELECT s.name, us.proficiency_level FROM userSkills us INNER JOIN skills s ON us.skill_id = s.skill_id WHERE us.user_id = ?");
        $skills_query->bind_param("i", $user_id);
        $skills_query->execute();
        $skills_result = $skills_query->get_result();

        // Fetch resume from the database
        $resume_query = $conn->prepare("SELECT resume FROM users WHERE user_id = ?");
        $resume_query->bind_param("i", $user_id);
        $resume_query->execute();
        $resume_result = $resume_query->get_result();
        $resume = $resume_result->fetch_assoc();

        // Update application status to 'reviewed'
        $update_query = $conn->prepare("UPDATE applicants SET application_status = 'reviewed' WHERE user_id = ?");
        $update_query->bind_param("i", $user_id);
        $update_query->execute();
    } else {
        // User not found, redirect back to previous page
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
} else {
    // User ID not provided, redirect back to previous page
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Include your CSS file -->
    <style>
        /* Additional CSS styles for user profile */
        .profile-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            min-height: 600px;
        }

        .profile-container h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .profile-details {
            margin-bottom: 20px;
        }

        .profile-details p {
            margin-bottom: 10px;
        }

        .skills {
            margin-bottom: 20px;
        }

        .skills ul {
            list-style-type: none;
            padding: 0;
        }

        .skills ul li {
            margin-bottom: 5px;
        }

        .resume-container {
            margin-bottom: 20px;
        }

        .resume-container iframe {
            width: 100%;
            height: 500px;
            border: none;
        }
        header {
            margin-bottom: 20px;
        }
        footer {
            margin-top: 20px;
        }

        .go-back-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 30px;
        }

        .go-back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?> <!-- Include header file -->

    <div class="profile-container">
    <a href="javascript:history.back()" class="go-back-btn">Go Back</a>
        <h2>User Profile</h2>
        <div class="profile-details">
            <p><strong>Name:</strong> <?php echo $user['f_name'] . ' ' . $user['l_name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            <p><strong>Gender:</strong> <?php echo $user['gender']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
        </div>
        <div class="skills">
            <h3>Skills:</h3>
            <ul>
                <?php while ($skill = $skills_result->fetch_assoc()) : ?>
                    <li><?php echo $skill['name'] . ' - ' . $skill['proficiency_level']; ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
        <div class="resume-container">
            <h3>Resume:</h3>
            <iframe src="data:application/pdf;base64,<?php echo base64_encode($resume['resume']); ?>" frameborder="0"></iframe>
        </div>
    </div>

    <?php include('footer.php'); ?> <!-- Include footer file -->
</body>
</html>
