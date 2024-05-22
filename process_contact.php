<?php
include 'db.php'; // Include your database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare SQL statement to insert data into the database
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    
    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the prepared statement
    if ($stmt->execute()) {
        $status_message = "Your message was sent successfully!";
    } else {
        // Log the error message
        error_log("Error executing SQL: " . $sql . "\nError: " . $conn->error);

        $status_message = "Error: Unable to send message. Please try again later.";
    }

    // Close statement
    $stmt->close();

    // Close database connection
    $conn->close();

    // Redirect back to contact page after submission
    header("Location: contact.php?status=" . urlencode($status_message));
    exit();
}
?>
