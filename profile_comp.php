<?php
include('header.php'); // Include the header
include('db.php'); // Include the database connection

// Check if company user is logged in
if (!isset($_SESSION['company_user'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Retrieve company's information from the database
if (isset($_SESSION['company_id'])) {
    $company_id = $_SESSION['company_id'];
    $query = $conn->prepare("SELECT * FROM companies WHERE company_id = ?");
    $query->bind_param("i", $company_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
        $company_name = $row['company_name'];
        $industry = $row['industry'];
        $size = $row['size'];
        $description = $row['description'];
        $address = $row['address'];
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Company Profile</title>
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
                <h2>Welcome, <?php echo isset($company_name) ? $company_name : 'New Company User'; ?>!</h2>
                <div class="profile-info">
                    <p><strong>Username:</strong> <?php echo $username; ?></p>
                    <p><strong>Email:</strong> <?php echo $email; ?></p>
                    <form action="update_profile_comp.php" method="post" enctype="multipart/form-data">
                        <label for="company_name">Company Name:</label>
                        <input type="text" id="company_name" name="company_name" value="<?php echo isset($company_name) ? $company_name : ''; ?>"><br>
                        <label for="industry">Industry:</label>
                        <input type="text" id="industry" name="industry" value="<?php echo isset($industry) ? $industry : ''; ?>"><br>
                        <label for="size">Size:</label>
                        <select id="size" name="size">
                            <option value="">Select Size</option>
                            <option value="Small" <?php if ($size === 'Small') echo 'selected'; ?>>Small</option>
                            <option value="Medium" <?php if ($size === 'Medium') echo 'selected'; ?>>Medium</option>
                            <option value="Large" <?php if ($size === 'Large') echo 'selected'; ?>>Large</option>
                        </select><br>
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"><?php echo isset($description) ? $description : ''; ?></textarea><br>
                        <label for="address">Address:</label>
                        <textarea id="address" name="address"><?php echo isset($address) ? $address : ''; ?></textarea><br>
                        <input type="submit" value="Save">
                    </form>

                </div>
            </div>
        </div>



        <?php
    } else {
        echo "<p>Error: Company information not found in the database.</p>"; // Error message if company information is not found
    }
} else {
    echo "<p>Error: Company ID not set.</p>"; // Error message if company ID is not set
}

include('footer.php'); // Include the footer
?>
