<?php
// Include header
include('header.php');

// Check if user is admin
if (!isset($_SESSION['admin'])) {
    // Redirect to login page if user is not admin
    header("Location: login.php");
    exit();
}
?>

<main>
    <h2>Manage</h2>
    <div class="card-container">
        <!-- Card 1: View/Delete Users and Company Users -->
        <div class="card">
            <h3><span class="emoji">👥</span>View/Delete Users</h3>
            <p>Manage regular users' accounts.</p>
            <a href="manage_user.php">Go to User Management</a>
        </div>

        <!-- Card 2: Add/Drop Categories -->
        <div class="card">
            <h3><span class="emoji">📋</span>Add/Drop Categories</h3>
            <p>Add or remove job categories.</p>
            <a href="manage_category.php">Go to Category Management</a>
        </div>

        <!-- Card 3: Add/Drop Skills -->
        <div class="card">
            <h3><span class="emoji">🛠️</span>Add/Drop Skills</h3>
            <p>Add or remove skills.</p>
            <a href="manage_skill.php">Go to Skill Management</a>
        </div>

        <!-- Card 4: View Contact Messages -->
        <div class="card">
            <h3><span class="emoji">📧</span>View Contact Messages</h3>
            <p>View messages submitted through contact form.</p>
            <a href="manage_message.php">Go to Contact Messages</a>
        </div>
    </div>
</main>

<?php
// Include footer
include('footer.php');
?>

<style>
    /* Style the main title */
    h2 {
        font-size: 3rem;
        margin-bottom: 30px;
        text-align: center;
    }

    /* Style the card container */
    .card-container {
        display: grid;
        grid-template-columns: repeat(2);
        gap: 20px;
        justify-content: center;
        margin-top: 20px;
    }

    /* Style the card */
    .card {
        width: 100%;
        max-width: 400px; /* Adjust card max-width as needed */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
        transition: transform 0.3s ease;
        text-align: center;
    }

    /* Hover effect */
    .card:hover {
        transform: scale(1.05);
    }

    /* Style the card title */
    .card h3 {
        color: #333;
        font-size: 24px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Style the card description */
    .card p {
        color: #666;
        font-size: 16px;
        margin-bottom: 20px;
    }

    /* Style the card link */
    .card a {
        display: inline-block;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    /* Hover effect for card link */
    .card a:hover {
        background-color: #0056b3;
    }

    /* Style the emoji */
    .emoji {
        font-size: 24px;
        margin-right: 10px;
    }
    header {
        margin-bottom: 20px;
    }
    footer{
        margin-top: 20px;
    }
</style>
