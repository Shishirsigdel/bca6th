<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
} else { 
    // Initialize the search term
    $search = '';
    $searchQuery = '';

    // Handle the search functionality
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
        $searchQuery = " WHERE tblbooks.BookName LIKE :search OR tblauthors.AuthorName LIKE :search OR tblcategory.CategoryName LIKE :search";
    }

    // Prepare the SQL query
    $sql = "SELECT tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.id as bookid, tblbooks.bookImage, tblbooks.isIssued 
            FROM tblbooks 
            JOIN tblcategory ON tblcategory.id = tblbooks.CatId 
            JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId" . $searchQuery;

    $query = $dbh->prepare($sql);
    
    // Bind the search parameter if it exists
    if ($searchQuery) {
        $searchParam = "%" . $search . "%";
        $query->bindParam(':search', $searchParam);
    }

    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Issued Books</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
        /* Additional CSS for the clear button */
        .search-container {
            position: relative;
        }
        .clear-button {
            position: absolute;
            right: 50px; /* Adjust as needed for spacing */
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            display: none; /* Initially hidden */
            font-size: 20px; /* Adjust size for visibility */
            color: red; /* Cross icon color */
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Issued Books</h4>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST" class="form-inline search-container" style="margin-bottom: 20px;">
                            <input type="text" name="search" class="form-control" placeholder="Search by Book Name, Author, or Category" value="<?php echo htmlentities($search); ?>" required />
                            <span class="clear-button" id="clear-search" onclick="clearSearch()">✖</span> <!-- Clear button -->
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Issued Books</div>
                            <div class="panel-body">
                                <?php
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) { 
                                ?>  
                                <div class="col-md-4" style="float:left; height:300px;">   
                                    <img src="admin/bookimg/<?php echo htmlentities($result->bookImage); ?>" width="100">
                                    <br /><b><?php echo htmlentities($result->BookName); ?></b><br />
                                    <?php echo htmlentities($result->CategoryName); ?><br />
                                    <?php echo htmlentities($result->AuthorName); ?><br />
                                    <?php echo htmlentities($result->ISBNNumber); ?><br />
                                    <?php if ($result->isIssued == '1'): ?>
                                        <p style="color:red;">Book Already issued</p>
                                    <?php endif; ?>
                                </div>
                                <?php 
                                    $cnt++;
                                    }
                                } else {
                                    echo "<p>No results found for your search.</p>";
                                } 
                                ?>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
       
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const clearButton = document.getElementById('clear-search');

            
            searchInput.addEventListener('input', function() {
                clearButton.style.display = searchInput.value ? 'block' : 'none';
            });

          
            if (searchInput.value) {
                clearButton.style.display = 'block';
            }
        });


        function clearSearch() {
            const searchInput = document.querySelector('input[name="search"]');
            searchInput.value = ''; 
            document.forms[0].submit(); 
    </script>
</body>
</html>
<?php } ?>
