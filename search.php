<?php
// Include header
include('header.php');
// Include database connection
include('db.php');

// Pagination setup
$results_per_page = 5; // Adjusted to display 5 job postings per page
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

// SQL to retrieve job postings
$sql = "SELECT companies.company_id, companies.company_name AS company_name, jobs.job_id, jobs.title, jobs.description 
        FROM jobs 
        INNER JOIN companies ON jobs.company_id = companies.company_id";
$result = mysqli_query($conn, $sql);
$number_of_results = mysqli_num_rows($result);
$number_of_pages = ceil($number_of_results / $results_per_page);
$this_page_first_result = ($page - 1) * $results_per_page;

// SQL to retrieve job postings for the current page
$sql = "SELECT companies.company_id, companies.company_name AS company_name, jobs.job_id, jobs.title, jobs.description 
        FROM jobs 
        INNER JOIN companies ON jobs.company_id = companies.company_id
        LIMIT $this_page_first_result, $results_per_page";
$result = mysqli_query($conn, $sql);

// Display job postings
?>
<main>
    <section>
        <h2>Job Postings</h2>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p><strong>Company:</strong> <a href='company_details.php?company_id=" . $row['company_id'] . "'>" . htmlspecialchars($row['company_name']) . "</a></p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>";
            // Check if user is not a company user or admin user before displaying the Apply button
            if (!isset($_SESSION['company_user']) && !isset($_SESSION['admin'])) {
                echo '<a class="apply-button" href="apply.php?job_id=' . $row['job_id'] . '">Apply</a>';
            } elseif (isset($_SESSION['admin'])) {
                // Display a message for admin users instead of the Apply button
                echo '<p><em>Admin users cannot apply for jobs.</em></p>';
            } else {
                // Display a message for company users instead of the Apply button
                echo '<p><em>Company users cannot apply for jobs.</em></p>';
            }
            echo "</div>";
        }
        ?>
    </section>

    <!-- Pagination -->
    <section class="pagination">
        <div>
            <?php
            // Display pagination links
            for ($pg = 1; $pg <= $number_of_pages; $pg++) {
                echo '<a href="search.php?page=' . $pg . '"';
                if ($pg == $page) {  // Corrected: Added $ before page
                    echo ' class="active"';
                }
                echo '>' . $pg . '</a> ';
            }
            ?>
        </div>
    </section>

</main>
<?php
// Include footer
include('footer.php');
?>
<style>
    .apply-button {
        margin-bottom: 20px;
    }
    em {
        color: red;
    }
    h2{
        font-size: 2.5rem;
        margin-bottom: 2rem;
    }
</style>
