<?php
include('header.php'); // Include the header
include('db.php'); // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Retrieve user's information from the database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
        $f_name = $row['f_name'];
        $l_name = $row['l_name'];
        $date_of_birth = $row['date_of_birth'];
        $phone = $row['phone'];
        $gender = $row['gender'];
        $address = $row['address'];
        $resume = $row['resume'];
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Profile</title>
            <style>
                /* Global Styles */
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }

                .container {
                    max-width: 800px;
                    margin: 20px auto 10px; /* Adjusted margin-bottom */
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    height: 100%;
                }

                h2 {
                    margin-top: 0;
                }

                form {
                    margin-top: 20px;
                    margin-bottom: 20px; /* Added margin-bottom */
                }

                label {
                    display: block;
                    margin-bottom: 5px;
                }

                input[type="text"],
                input[type="date"],
                input[type="file"],
                select,
                textarea {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                }

                input[type="submit"] {
                    background-color: #007bff;
                    color: #fff;
                    border: none;
                    padding: 15px 40px; /* Increased padding */
                    border-radius: 8px; /* Increased border-radius */
                    cursor: pointer;
                    margin-top: 30px; /* Adjusted margin-top */
                }

                input[type="submit"]:hover {
                    background-color: #0056b3;
                }

                .profile-container {
                    min-height: calc(50vh - 50px); /* Adjusted minimum height */
                    padding-bottom: 50px; /* Added padding-bottom */
                    margin-top: 50px;
                }

                .profile-resume {
                    margin-top: 20px;
                }

                .profile-resume p {
                    margin: 0;
                }

                /* Media Query for smaller screens */
                @media (max-width: 600px) {
                    .container {
                        padding: 10px;
                    }

                    input[type="text"],
                    input[type="date"],
                    input[type="file"],
                    select,
                    textarea {
                        width: calc(100% - 20px);
                    }
                }
            </style>
        </head>

        <body>
            <div class="profile-container">
                <div class="container">
                    <h2>Welcome, <?php echo $username; ?>!</h2>
                    <div class="profile-info">
                        <p><strong>Username:</strong> <?php echo $username; ?></p>
                        <p><strong>Email:</strong> <?php echo $email; ?></p>
                        <form action="update_profile.php" method="post" enctype="multipart/form-data">
                            <label for="f_name">First Name:</label>
                            <input type="text" id="f_name" name="f_name" value="<?php echo $f_name !== null ? $f_name : ''; ?>"><br>
                            <label for="l_name">Last Name:</label>
                            <input type="text" id="l_name" name="l_name" value="<?php echo $l_name !== null ? $l_name : ''; ?>"><br>
                            <label for="date_of_birth">Date of Birth:</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $date_of_birth !== null ? $date_of_birth : ''; ?>"><br>
                            <label for="phone">Phone:</label>
                            <input type="text" id="phone" name="phone" value="<?php echo $phone !== null ? $phone : ''; ?>"><br>
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender">
                                <option value="" <?php if (empty($gender)) echo 'selected'; ?>></option>
                                <option value="M" <?php if ($gender === 'M') echo 'selected'; ?>>Male</option>
                                <option value="F" <?php if ($gender === 'F') echo 'selected'; ?>>Female</option>
                                <option value="Other" <?php if ($gender === 'Other') echo 'selected'; ?>>Other</option>
                            </select><br>
                            <label for="address">Address:</label>
                            <textarea id="address" name="address"><?php echo $address !== null ? $address : ''; ?></textarea><br>
                            <?php if ($resume !== null) : ?>
                                <div class="profile-resume">
                                    <p><strong>Resume:</strong></p>
                                    <a href="view_resume.php" target="_blank">View Resume</a>
                                    <label for="new_resume">Upload New Resume:</label>
                                    <input type="file" name="resume" id="resume">
                                    <input type="submit" value="Save">
                                </div>
                            <?php else : ?>
                                <label for="resume">Upload Resume:</label>
                                <input type="file" name="resume" id="resume">
                                <input type="submit" value="Save">
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

        <?php
    } else {
        echo "<p>Error: User information not found in the database.</p>"; // Error message if user information is not found
    }
} else {
    echo "<p>Error: User ID not set.</p>"; // Error message if user ID is not set
}

include('footer.php'); // Include the footer
?>
