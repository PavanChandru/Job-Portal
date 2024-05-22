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

// Initialize variable to track success or failure of job posting
$posted = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $company_id = $_SESSION['company_id'];
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $salary = $_POST['salary'];

    // Validate salary input
    if (!is_numeric($salary)) {
        echo "Error: Salary must be a numeric value.";
        exit();
    }

    // Prepare SQL statement to insert job into database
    $query = $conn->prepare("INSERT INTO jobs (company_id, category_id, title, description, salary) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("iisss", $company_id, $category_id, $title, $description, $salary);

    // Execute SQL query
    if ($query->execute()) {
        // Set posted flag to true
        $posted = true;
    } else {
        echo "Error: Failed to post job.";
    }
}

// Close database connection
$conn->close();
?>

<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <style>
 /* post_job.css */

/* Global Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    max-width: 800px;
    margin: 20px auto; /* Adjusted margin */
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
    padding: 15px 40px;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 30px;
}

input[type="submit"]:hover {
    background-color: #0056b3;
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

<div class="container">
    <h2>Post Job</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id">
            <option value="">Select Category</option> <!-- Added default option -->
            <!-- Retrieve categories from the database -->
            <?php
            include('db.php');
            $result = $conn->query("SELECT * FROM categories");
            while ($row = $result->fetch_assoc()) {
                echo "<option value=\"" . $row['category_id'] . "\">" . $row['name'] . "</option>";
            }
            $conn->close();
            ?>
        </select><br>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title"><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea><br>
        <label for="salary">Salary:</label>
        <input type="text" id="salary" name="salary"><br>
        <input type="submit" value="Post Job">
    </form>
    <?php
    // Display success message if job was successfully posted
    if ($posted) {
        echo '<script>alert("Job successfully posted!");</script>';
    }
    ?>
</div>

</body>
</html>

<?php include('footer.php'); ?>
