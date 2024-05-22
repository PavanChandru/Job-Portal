<?php
// Include database connection
include('db.php');

// Initialize error message variable
$error_message = "";

if (isset($_POST['login'])) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve the hashed password and company ID from the companies table
    $query = $conn->prepare("SELECT company_id, password FROM companies WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['company_user'] = true;
            $_SESSION['company_id'] = $row['company_id']; // Set company ID in session
            // Set company name in session
            $_SESSION['company_name'] = $username; // Assuming the company username is used as the company name
            header("Location: index.php"); // Redirect to index.php after successful login for company users
            exit();
        }
         else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .toggle-container {
            text-align: left; /* Align toggle to the left */
            margin-bottom: 20px;
        }
        .toggle-container label {
            display: inline-block;
            margin-right: 20px;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #3cb371; /* Default color set to green */
            -webkit-transition: .4s;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #2196F3; /* Blue color when toggled */
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
        .toggle-status {
            text-align: center;
            margin-bottom: 10px;
            font-size: 18px; /* Increase font size */
            color: #555; /* Dark grey color */
        }
        .login__submit.company {
            background-color: #4b86f0; /* Bluish color for company user */
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <div class="login-container">
        <div class="toggle-status">
            <p id="userStatus">Log in as company user</p>
        </div>
        <div class="toggle-container">
            <label class="switch">
                <input type="checkbox" id="toggleButton" class="active" onclick="toggleUser()" checked> <!-- Note: Toggle button starts on the right -->
                <span class="slider round"></span>
            </label>
        </div>
        <form class="form-login" action="" method="POST">
            <ul class="login-nav">
                <li class="login-nav__item active">
                    <a href="#">Log In</a>
                </li>
                <li class="login-nav__item">
                    <a href="signup.php">Sign Up</a>
                </li>
            </ul>
            <label for="username" class="login__label">Username:</label>
            <input type="text" id="username" class="login__input" name="username" required>
            <label for="password" class="login__label">Password:</label>
            <input type="password" id="password" class="login__input" name="password" required>
            <button type="submit" class="login__submit company" name="login">Login</button> <!-- Note: Added 'company' class to change button color to blue -->
            <?php if (!empty($error_message)): ?>
                <p class="login__forgot" style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <p class="login__forgot">Not a member? <a href="signup.php">Sign up now</a></p>
        </form>
    </div>
    <?php include('footer.php'); ?>
    <script>
    function toggleUser() {
        const toggleButton = document.getElementById('toggleButton');
        const slider = document.querySelector('.slider');
        const userStatus = document.getElementById('userStatus');

        if (toggleButton.checked) {
            slider.style.backgroundColor = '#2196F3'; // Blue color
            userStatus.textContent = "Log in as company user";
            document.querySelector('.login__submit').classList.add('company'); // Add class for company user
        } else {
            slider.style.backgroundColor = '#3cb371'; // Green color
            userStatus.textContent = "Log in as applicant user";
            document.querySelector('.login__submit').classList.remove('company'); // Remove class for applicant user
            // Add a delay before redirecting to login.php
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 200); // Delay of 0.2 seconds (200 milliseconds)
        }
    }
</script>



</body>
</html>
