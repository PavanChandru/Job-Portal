<?php
// Include header
include('header.php');

// Check if user is admin
if (!isset($_SESSION['admin'])) {
    // Redirect to login page if user is not admin
    header("Location: login.php");
    exit();
}

// Include database connection
include('db.php');

// Function to add a new skill
function addSkill($conn, $name) {
    $add_query = $conn->prepare("INSERT INTO skills (name) VALUES (?)");
    $add_query->bind_param("s", $name);
    $add_query->execute();
}

// Function to delete a skill by skill_id
function deleteSkill($conn, $skill_id) {
    $delete_query = $conn->prepare("DELETE FROM skills WHERE skill_id = ?");
    $delete_query->bind_param("i", $skill_id);
    $delete_query->execute();
}

// Fetch all skills
$skill_query = $conn->prepare("SELECT skill_id, name FROM skills");
$skill_query->execute();
$skill_result = $skill_query->get_result();

// Check if the form to add a new skill is submitted
if (isset($_POST['add_skill'])) {
    $new_name = $_POST['new_name'];
    
    // Add the new skill
    addSkill($conn, $new_name);
    // Refresh the page after adding
    header("Location: manage_skill.php");
    exit();
}

// Check if the form to delete a skill is submitted
if (isset($_POST['delete_skill'])) {
    $skill_id = $_POST['skill_id'];
    
    // Delete the skill
    deleteSkill($conn, $skill_id);
    // Refresh the page after deletion
    header("Location: manage_skill.php");
    exit();
}
?>

<main>
    <h2 style="text-align: center;">Skill Management</h2>

    <!-- Card to add a new skill -->
    <div class="add-skill-card" style="max-width: 800px; margin: 0 auto; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #f9f9f9;">
        <h3>Add New Skill</h3>
        <form action="" method="post">
            <label for="new_name" style="font-size: 1.2rem; font-weight: bold;">Skill Name:</label>
            <input type="text" id="new_name" name="new_name" style="width: 100%; padding: 8px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ccc;" required><br>
            <button type="submit" name="add_skill" style="margin-bottom: 40px; padding: 10px 20px; font-size: 1.2rem; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">Add Skill</button>
        </form>
    </div>

    <!-- Table for existing skills -->
    <div class="existing-skills-table" style="max-width: 800px; margin: 30px auto;">
        <h3>Existing Skills</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 10px; border-bottom: 1px solid #ccc;">Skill Name</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ccc;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $skill_result->fetch_assoc()) : ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?php echo $row['name']; ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="skill_id" value="<?php echo $row['skill_id']; ?>">
                                <button type="submit" name="delete_skill" style="padding: 5px 10px; font-size: 1rem; background-color: #dc3545; color: #fff; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
// Include footer
include('footer.php');
?>

<style>
    th, td {
        text-align: left;
    }
    header {
        margin-bottom: 20px;
    }
    footer {
        margin-top: 20px;
    }
</style>
