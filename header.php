<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT For Hire</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
            border-radius: 4px;
            overflow: hidden;
            padding-top: 8px;
        }

        .dropdown-content a {
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
            color: #000; /* Set font color to black */
        }

        .dropdown-content a.username {
            color: #000; /* Set font color to black */
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown .dropbtn {
            background-color: transparent;
            border: none;
            color: white;
            font-family: "Montserrat", sans-serif;
            font-size: 1em;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: color 0.3s;
            border: 2px solid transparent;
            border-radius: 4px;
            padding: 8px 16px;
        }

        .dropdown:hover .dropbtn {
            border-color: #3e8e41;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <header>
        <div class="title-container">
            <h1>IT For Hire</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="service.php">Service</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="search.php">Search Jobs</a></li>
                <?php
                if (isset($_SESSION['admin'])) {
                    // Display admin-specific links
                    echo '<li><a href="manage.php">Manage</a></li>';
                    echo '<li><a href="logout.php">Logout</a></li>';
                } elseif (isset($_SESSION['company_user'])) {
                    // Display "Post Job" link for company users
                    echo '<li><a href="post_job.php">Post Job</a></li>';
                    // Display dropdown menu for company users
                    echo '<li class="dropdown">';
                    echo '<button class="dropbtn">' . $_SESSION['company_name'] . '</button>';
                    echo '<div class="dropdown-content">';
                    echo '<a href="profile_comp.php" class="username">Profile</a>';
                    echo '<a href="posted_job.php" class="username">Posted Jobs</a>';
                    echo '<a href="logout.php" class="username">Logout</a>';
                    echo '</div>';
                    echo '</li>';
                } elseif (isset($_SESSION['user'])) {
                    // Display dropdown menu for logged-in user
                    echo '<li class="dropdown">';
                    echo '<button class="dropbtn">' . $_SESSION['username'] . '</button>';
                    echo '<div class="dropdown-content">';
                    echo '<a href="profile.php" class="username">My Profile</a>';
                    echo '<a href="skill.php" class="username">My Skills</a>';
                    echo '<a href="savedjob.php" class="username">Applied Jobs</a>'; // New link for Saved Jobs
                    echo '<a href="logout.php" class="username">Logout</a>';
                    echo '</div>';
                    echo '</li>';
                } else {
                    // Display login and sign-up links for non-logged-in user
                    echo '<li><a href="login.php">Login</a></li>';
                    echo '<li><a href="signup.php">Sign Up</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
</body>
</html>
