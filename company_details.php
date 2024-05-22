<?php
include('header.php');
include('db.php');

$page = $_GET['page'] ?? 1; // Default to page 1 if no page is specified

if (isset($_GET['company_id'])) {
    $company_id = intval($_GET['company_id']); // Ensuring the ID is an integer to prevent SQL injection

    // Prepare SQL to fetch company details
    $sql = "SELECT * FROM companies WHERE company_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $company_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Display company details
        echo "<main><section>";
        echo "<a href='search.php?page=" . $page . "' class='apply-button'>Back to Search Results</a>";
        echo "<h2>" . htmlspecialchars($row['company_name']) . "</h2>";
        echo "<p><strong>Industry:</strong> " . htmlspecialchars($row['industry']) . "</p>";
        echo "<p><strong>Size:</strong> " . htmlspecialchars($row['size']) . "</p>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>";
        echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
        echo "</section></main>";
    } else {
        echo "<p>Company not found.</p>";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "<p>Invalid company ID.</p>";
}

include('footer.php');
?>

<style>
    body {
        font-family: Arial, sans-serif; /* Provides a clean, modern look */
        background-color: #f4f4f4; /* Light grey background for a subtle texture */
        margin: 0;
        padding: 0;
    }
    main {
        background: white; /* Ensures content stands out from the background */
        width: 80%; /* Limits the width of the content area */
        margin: 20px auto; /* Centers the content on the page and provides spacing from the top/bottom */
        padding: 20px; /* Spacing inside the content area */
        box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Subtle shadow for depth */
    }
    section {
        border-bottom: 1px solid #ccc; /* Adds a subtle line to separate sections */
        padding-bottom: 20px; /* Spacing at the bottom of each section */
        margin-bottom: 20px; /* Spacing between sections */
    }
    h2 {
        color: #333; /* Dark grey color for the title for better readability */
        font-size: 24px; /* Larger font size for headings */
    }
    p {
        color: #666; /* Medium grey color for paragraph text */
        font-size: 16px; /* Comfortable reading size for text */
        line-height: 1.6; /* Space between lines for easier reading */
    }
    p strong {
        color: #333; /* Darker color for emphasis */
    }
    .apply-button, .active {
        background-color: #007BFF; /* Bright blue for primary buttons and active links */
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
        margin-bottom: 30px; /* Adds margin at the bottom */
        margin-top: -30px;

    }
    .apply-button:hover, .active:hover {
        background-color: #0056b3; /* Darker blue on hover for interactive feel */
    }
    em {
        color: red; /* Red color for emphasis and warnings */
    }
</style>
