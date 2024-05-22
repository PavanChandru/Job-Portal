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

// Function to delete a message by id
function deleteMessage($conn, $id) {
    $delete_query = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $delete_query->bind_param("i", $id);
    $delete_query->execute();
}

// Pagination setup
$results_per_page = 5;

// Count total number of messages
$count_query = $conn->query("SELECT COUNT(*) AS total FROM contact_messages");
$total_results = $count_query->fetch_assoc()['total'];
$number_of_pages = ceil($total_results / $results_per_page);

// Get current page number
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

$this_page_first_result = ($page - 1) * $results_per_page;

// Fetch messages for the current page
$message_query = $conn->prepare("SELECT id, name, email, message, submission_date FROM contact_messages ORDER BY submission_date DESC LIMIT ?, ?");
$message_query->bind_param("ii", $this_page_first_result, $results_per_page);
$message_query->execute();
$message_result = $message_query->get_result();
?>

<main>
    <h2 style="text-align: center;">Message Management</h2>

    <!-- Table for displaying messages -->
    <div class="message-table" style="max-width: 1000px; margin: 0 auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 10px; border-bottom: 1px solid #ccc; width: 150px;">Name</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ccc; width: 150px;">Email</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ccc; width: 400px;">Message</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ccc; width: 150px;">Submission Date</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ccc; width: 150px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $message_result->fetch_assoc()) : ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?php echo $row['name']; ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?php echo $row['email']; ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc; text-align: center;"><?php echo $row['message']; ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?php echo $row['submission_date']; ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc; text-align: center;">
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="page" value="<?php echo $page; ?>"> <!-- Store current page number -->
                                <button type="submit" name="delete_message" style="padding: 5px 10px; font-size: 1rem; background-color: #dc3545; color: #fff; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination" style="text-align: center; margin-top: 20px;">
            <?php for ($page_num = 1; $page_num <= $number_of_pages; $page_num++) : ?>
                <a href="?page=<?php echo $page_num; ?>" style="padding: 5px 10px; margin-right: 5px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;"><?php echo $page_num; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</main>

<?php
// Delete message if the delete button is clicked
if (isset($_POST['delete_message'])) {
    $message_id = $_POST['message_id'];
    deleteMessage($conn, $message_id);
    $page = $_POST['page']; // Retrieve stored page number
    // Show JavaScript alert and redirect back to the current page with the stored page number
    echo '<script>alert("Message deleted successfully."); window.location.href = "manage_message.php?page=' . $page . '";</script>';
    exit();
}

// Include footer
include('footer.php');
?>

<style>
    header {
        margin-bottom: 20px;
    }
    footer {
        margin-top: 20px;
    }
</style>