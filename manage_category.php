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

// Function to add a new category
function addCategory($conn, $name, $description) {
    $add_query = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $add_query->bind_param("ss", $name, $description);
    $add_query->execute();
}

// Function to delete a category by category_id
function deleteCategory($conn, $category_id) {
    $delete_query = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
    $delete_query->bind_param("i", $category_id);
    $delete_query->execute();
}

// Fetch all categories
$category_query = $conn->prepare("SELECT category_id, name, description FROM categories");
$category_query->execute();
$category_result = $category_query->get_result();

// Check if the form to add a new category is submitted
if (isset($_POST['add_category'])) {
    $new_name = $_POST['new_name'];
    $new_description = $_POST['new_description'];
    
    // Add the new category
    addCategory($conn, $new_name, $new_description);
    // Refresh the page after adding
    header("Location: manage_category.php");
    exit();
}

// Check if the form to delete a category is submitted
if (isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];
    
    // Delete the category
    deleteCategory($conn, $category_id);
    // Refresh the page after deletion
    header("Location: manage_category.php");
    exit();
}
?>

<main>
    <h2 style="text-align: center;">Category Management</h2>

    <!-- Form to add a new category -->
    <div class="add-category" style="max-width: 800px; margin: 0 auto;">
        <h3>Add New Category</h3>
        <form action="" method="post">
            <label for="new_name">Name:</label>
            <input type="text" id="new_name" name="new_name" style="max-width: 500px;" required><br>
            <label for="new_description">Description:</label><br>
            <textarea id="new_description" name="new_description" style="max-width: 500px;"></textarea><br>
            <button type="submit" name="add_category" style="margin-bottom: 40px;">Add Category</button>
        </form>
    </div>

    <!-- List of existing categories -->
    <div class="existing-categories" style="max-width: 800px; margin: 0 auto;">
        <h3>Existing Categories</h3>
        <ul>
            <?php while ($row = $category_result->fetch_assoc()) : ?>
                <li>
                    <div class="category-info">
                        <span class="category-name"><?php echo $row['name']; ?></span> - 
                        <span class="category-description"><?php echo $row['description']; ?></span>
                    </div>
                    <form action="" method="post" class="delete-form">
                        <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
                        <button type="submit" name="delete_category">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</main>


<?php
// Include footer
include('footer.php');
?>

<style>
    /* Style the main title */
    h2 {
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 80px;
        margin-top: 40px;
    }

    /* Style the add category section */
    .add-category {
        margin-bottom: 50px;
        text-align: center;
    }

    .add-category h3 {
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .add-category form {
        margin: 0 auto; /* Center the form */
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .add-category label {
        font-size: 1.2rem;
        margin-bottom: 5px;
    }

    .add-category input[type="text"],
    .add-category textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .add-category button {
        padding: 10px 20px;
        font-size: 1.2rem;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .add-category button:hover {
        background-color: #0056b3;
    }

    /* Style the existing categories list */
    .existing-categories {
        margin-bottom: 50px;
    }

    .existing-categories h3 {
        font-size: 1.8rem;
        margin-bottom: 10px;
        text-align: center;
        margin-top: 50px;
    }

    .existing-categories ul {
        list-style-type: none;
        padding: 0;
    }

    .existing-categories li {
        margin-bottom: 15px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-info {
        flex-grow: 1;
    }

    .category-name {
        font-weight: bold;
    }

    .category-description {
        color: #666;
    }

    .delete-form {
        display: inline;
    }

    .delete-form button {
        padding: 5px 10px;
        font-size: 1.1rem;
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .delete-form button:hover {
        background-color: #c82333;
    }
    footer {
        margin-top: 20px;
    }
</style>
