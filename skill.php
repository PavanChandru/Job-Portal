<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('header.php'); // Include the header
include('db.php'); // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Fetch skills from the database excluding the ones already added by the user
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT * FROM skills WHERE skill_id NOT IN (SELECT skill_id FROM userSkills WHERE user_id = ?)");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

// Fetch user's skills from the database
$user_skills_query = $conn->prepare("SELECT s.name, us.proficiency_level, us.user_skill_id FROM userSkills us JOIN skills s ON us.skill_id = s.skill_id WHERE us.user_id = ?");
$user_skills_query->bind_param("i", $user_id);
$user_skills_query->execute();
$user_skills_result = $user_skills_query->get_result();

// Handle skill addition
if (isset($_POST['add_skill'])) {
    $skill_id = $_POST['skill_id'];
    $proficiency_level = $_POST['proficiency_level'];
    
    // Insert the skill into userSkills table
    $insert_query = $conn->prepare("INSERT INTO userSkills (user_id, skill_id, proficiency_level) VALUES (?, ?, ?)");
    $insert_query->bind_param("iis", $user_id, $skill_id, $proficiency_level);
    if ($insert_query->execute()) {
        // Skill added successfully
        header("Location: skill.php");
        exit();
    } else {
        // Error occurred
        $error_message = "Error adding skill. Please try again.";
    }
}

// Handle skill deletion
if (isset($_POST['delete_skill'])) {
    $user_skill_id = $_POST['user_skill_id'];
    
    // Delete the skill from userSkills table
    $delete_query = $conn->prepare("DELETE FROM userSkills WHERE user_skill_id = ?");
    $delete_query->bind_param("i", $user_skill_id);
    if ($delete_query->execute()) {
        // Skill deleted successfully
        header("Location: skill.php");
        exit();
    } else {
        // Error occurred
        $error_message = "Error deleting skill. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Skills</title>
    <style>
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        h3 {
            margin-bottom: 10px;
            margin-top: 50px;
        }

        .current-skills {
            list-style-type: none;
            padding: 0;
        }

        .current-skills li {
            margin-bottom: 10px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-btn {
            background-color: #ff6347;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #d6341f;
        }

        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        .add-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .add-btn:hover {
            background-color: #0056b3;
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
    <div class="container">
        <h2>My Skills</h2>
        <?php if (isset($error_message)) echo "<p class='error-message'>$error_message</p>"; ?>
        
        <!-- Display user's skills -->
        <h3>My Current Skills</h3>
        <ul class="current-skills">
            <?php while ($row = $user_skills_result->fetch_assoc()): ?>
                <li><?= $row['name'] ?> (<?= $row['proficiency_level'] ?>) 
                    <form action="skill.php" method="post" style="display: inline;">
                        <input type="hidden" name="user_skill_id" value="<?= $row['user_skill_id'] ?>">
                        <input type="submit" name="delete_skill" value="Delete" class="delete-btn">
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>

        <!-- Add new skill form -->
        <h3>Add New Skill</h3>
        <form action="skill.php" method="post">
            <label for="skill_id">Select Skill:</label>
            <select name="skill_id" id="skill_id">
                <?php while ($skill = $result->fetch_assoc()): ?>
                    <option value="<?= $skill['skill_id'] ?>"><?= $skill['name'] ?></option>
                <?php endwhile; ?>
            </select><br>
            <label for="proficiency_level">Proficiency Level:</label>
            <select name="proficiency_level" id="proficiency_level">
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Expert">Expert</option>
            </select><br>
            <input type="submit" name="add_skill" value="Add Skill" class="add-btn">
        </form>
    </div>
</body>
</html>

<?php include('footer.php'); // Include the footer ?>
