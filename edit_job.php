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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $salary = $_POST['salary'];

    // Validate salary input
    if (!is_numeric($salary)) {
        echo "Error: Salary must be a numeric value.";
        exit();
    }

    // Prepare SQL statement to update job in the database
    $query = $conn->prepare("UPDATE jobs SET category_id = ?, title = ?, description = ?, salary = ? WHERE job_id = ?");
    $query->bind_param("isssi", $category_id, $title, $description, $salary, $job_id);

    // Execute SQL query
    if ($query->execute()) {
        // Redirect to posted jobs page after successful update
        header("Location: posted_job.php");
        exit();
    } else {
        echo "Error: Failed to update job.";
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            min-height: 600px;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select,
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
            padding-right: 40px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
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
        <h2>Edit Job</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?job_id=' . $job_id; ?>" method="post">
            <!-- Display form with pre-filled job details -->
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id">
                <!-- Retrieve categories from the database -->
                <?php
                include('db.php');
                $result = $conn->query("SELECT * FROM categories");
                while ($cat_row = $result->fetch_assoc()) {
                    $selected = ($cat_row['category_id'] == $row['category_id']) ? 'selected' : '';
                    echo "<option value=\"" . $cat_row['category_id'] . "\" $selected>" . $cat_row['name'] . "</option>";
                }
                $conn->close();
                ?>
            </select><br>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $row['title']; ?>"><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo $row['description']; ?></textarea><br>
            <label for="salary">Salary:</label>
            <input type="text" id="salary" name="salary" value="<?php echo $row['salary']; ?>"><br>
            <input type="submit" value="Update Job">
        </form>
    </div>

    <?php include('footer.php'); ?> <!-- Include footer file -->
</body>
</html>
