<?php
include('db.php');  // Include database connection

$error_message = ""; // Initialize error message variable

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if password meets strength requirements
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        // Check if email already exists
        $email_query = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $email_query->bind_param("s", $email);
        $email_query->execute();
        $email_result = $email_query->get_result();

        if ($email_result->num_rows > 0) {
            $error_message = "Email already in use!";
        } else {
            // Check if username already exists
            $username_query = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $username_query->bind_param("s", $username);
            $username_query->execute();
            $username_result = $username_query->get_result();

            if ($username_result->num_rows > 0) {
                $error_message = "Username already in use!";
            } else {
                // Hash the password for security
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert into database
                $query = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $query->bind_param("sss", $username, $email, $hashed_password);
                $query->execute();

                if ($query->affected_rows > 0) {
                    // Redirect to login page after successful registration
                    echo '<script>alert("You have successfully registered for an account!");';
                    echo 'setTimeout(function(){ window.location.href = "login.php"; }, 1000);</script>';
                    header("Location: login.php");
                    exit();
                } else {
                    $error_message = "Registration failed!";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .password-rules {
            font-size: 12px;
            color: #666;
            display: block;
            margin-bottom: 8px;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 4px;
        }
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const passwordRules = document.querySelector('.password-rules');
            const errorMessage = document.querySelector('.error-message');

            // Hide password rules description when password input is clicked
            passwordInput.addEventListener('click', function() {
                passwordRules.style.display = 'none';
            });

            // Hide password rules description when the user starts typing
            passwordInput.addEventListener('input', function() {
                passwordRules.style.display = 'none';
            });

            // Check password requirements when focus leaves password input
            passwordInput.addEventListener('blur', function() {
                const password = passwordInput.value;

                if (!isValidPassword(password)) {
                    errorMessage.style.display = 'block';
                } else {
                    errorMessage.style.display = 'none';
                }
            });

            // Function to validate password requirements
            function isValidPassword(password) {
                const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                return regex.test(password);
            }
        });
    </script>

</head>
<body>
    <?php include('header.php'); ?>
    <div class="login-container">
    <div class="toggle-status">
            <p id="userStatus">Sign up as applicant user</p>
        </div>
        <div class="toggle-container">
            <label class="switch">
                <input type="checkbox" id="toggleButton" class="active" onclick="toggleUser()">
                <span class="slider round"></span>
            </label>
        </div>
        <form class="form-login" action="" method="POST">
            <ul class="login-nav">
                <li class="login-nav__item">
                    <a href="login.php">Log In</a>
                </li>
                <li class="login-nav__item active">
                    <a href="#">Sign Up</a>
                </li>
            </ul>
            <label for="username" class="login__label">Username:</label>
            <input type="text" id="username" class="login__input" name="username" required>

            <label for="email" class="login__label">Email:</label>
            <input type="email" id="email" class="login__input" name="email" required>

            <label for="password" class="login__label">Password:</label>
            <input type="password" id="password" class="login__input" name="password" required>
            <div class="password-rules">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</div>

            <label for="confirm_password" class="login__label">Confirm Password:</label>
            <input type="password" id="confirm_password" class="login__input" name="confirm_password" required>
            <div class="error-message" style="display: none;">Password does not meet the requirements.</div>

            <button type="submit" class="login__submit" name="register">Sign Up</button>
            
            <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <p class="login__forgot">Already a member? <a href="login.php">Log in here</a></p>
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
            userStatus.textContent = "Sign up as company user";
            // Redirect to signup_comp.php with a delay
            setTimeout(function() {
                window.location.href = 'signup_comp.php';
            }, 200); // Delay of 0.2 seconds (200 milliseconds)
        } else {
            slider.style.backgroundColor = '#3cb371'; // Green color
            userStatus.textContent = "Sign up as applicant user";
        }
    }
</script>

</body>
</html>
