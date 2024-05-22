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

// Function to delete user by user_id
function deleteUser($conn, $user_id) {
    $delete_query = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $delete_query->bind_param("i", $user_id);
    $delete_query->execute();
}

// Fetch all users
$user_query = $conn->prepare("SELECT user_id, f_name, l_name, email FROM users");
$user_query->execute();
$user_result = $user_query->get_result();

// Fetch all company users
$company_query = $conn->prepare("SELECT company_id, company_name, email FROM companies");
$company_query->execute();
$company_result = $company_query->get_result();

// Pagination setup for users
$results_per_page_users = 10;
$number_of_results_users = $user_result->num_rows;
$number_of_pages_users = ceil($number_of_results_users / $results_per_page_users);

// Get current page number for users
if (!isset($_GET['page_users'])) {
    $page_users = 1;
} else {
    $page_users = $_GET['page_users'];
}

$this_page_first_result_users = ($page_users - 1) * $results_per_page_users;

// Pagination setup for companies
$results_per_page_companies = 10;
$number_of_results_companies = $company_result->num_rows;
$number_of_pages_companies = ceil($number_of_results_companies / $results_per_page_companies);

// Get current page number for companies
if (!isset($_GET['page_companies'])) {
    $page_companies = 1;
} else {
    $page_companies = $_GET['page_companies'];
}

$this_page_first_result_companies = ($page_companies - 1) * $results_per_page_companies;

// Fetch users for the current page
$user_query_pagination = $conn->prepare("SELECT user_id, f_name, l_name, email FROM users LIMIT ?, ?");
$user_query_pagination->bind_param("ii", $this_page_first_result_users, $results_per_page_users);
$user_query_pagination->execute();
$user_result_pagination = $user_query_pagination->get_result();

// Fetch companies for the current page
$company_query_pagination = $conn->prepare("SELECT company_id, company_name, email FROM companies LIMIT ?, ?");
$company_query_pagination->bind_param("ii", $this_page_first_result_companies, $results_per_page_companies);
$company_query_pagination->execute();
$company_result_pagination = $company_query_pagination->get_result();
?>

<main>
    <h2>User Management</h2>

    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'user')" id="defaultOpen">Users</button>
        <button class="tablinks" onclick="openTab(event, 'company')">Companies</button>
    </div>

    <!-- Users Table -->
    <div id="user" class="tabcontent">
        <h3>Users</h3>
        <table>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $user_result_pagination->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['f_name']; ?></td>
                    <td><?php echo $row['l_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><button class="delete-btn-user" onclick="deleteUser(<?php echo $row['user_id']; ?>)">Delete</button></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pagination for users -->
        <div class="pagination">
            <?php for ($page = 1; $page <= $number_of_pages_users; $page++) : ?>
                <a href="?page_users=<?php echo $page; ?>" class="<?php if ($page == $page_users) echo 'active'; ?>"><?php echo $page; ?></a>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Companies Table -->
    <div id="company" class="tabcontent">
        <h3>Companies</h3>
        <table>
            <tr>
                <th>Company ID</th>
                <th>Company Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $company_result_pagination->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['company_id']; ?></td>
                    <td><?php echo $row['company_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><button class="delete-btn-company" onclick="deleteCompany(<?php echo $row['company_id']; ?>)">Delete</button></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pagination for companies -->
        <div class="pagination">
            <?php for ($page = 1; $page <= $number_of_pages_companies; $page++) : ?>
                <a href="?page_companies=<?php echo $page; ?>" class="<?php if ($page == $page_companies) echo 'active'; ?>"><?php echo $page; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</main>

<?php
// Include footer
include('footer.php');
?>

<!-- Script to handle tab functionality -->
<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    document.getElementById("defaultOpen").click(); // Open the default tab on page load
</script>

<!-- Script to handle user deletion -->
<script>
    function deleteUser(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            window.location.href = "delete_user.php?user_id=" + userId;
        }
    }
</script>

<!-- Script to handle company deletion -->
<script>
    function deleteCompany(companyId) {
        if (confirm("Are you sure you want to delete this company?")) {
            window.location.href = "delete_company.php?company_id=" + companyId;
        }
    }
</script>

<style>
    /* Style the tabs */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }

    /* Style the table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    /* Style the delete button */
    .delete-btn-user {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        padding: 5px 10px;
    }

    .delete-btn-company {
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        padding: 5px 10px;
    }

    .delete-btn-user:hover {
        background-color: #0056b3;
    }

    .delete-btn-company:hover {
        background-color: #218838;
    }

    /* Pagination */
    .pagination {
        margin-top: -30px;
    }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
        margin-top: 50px;
    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
    }

    .pagination a:hover:not(.active) {background-color: #ddd;}

    header {
        margin-bottom: 20px;
    }
    footer{
        margin-top: 100px;
    }
</style>
