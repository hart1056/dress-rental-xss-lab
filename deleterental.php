<?php
require_once 'core/models.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// check kung may valid rental id
if (!isset($_GET['rental_id']) || !is_numeric($_GET['rental_id'])) {
    header("Location: index.php");
    exit();
}

// get yung rental record
$rental_id = (int) $_GET['rental_id'];
$rental = getRentalByID($pdo, $rental_id);

// balik sa list kung walang nahanap
if (!$rental) {
    header("Location: index.php");
    exit();
}

// get customer id para makabalik sa tamang rental page
$customer_id = $rental['customer_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Rental</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Delete Rental Record</h1>
        <p>Please confirm before deleting this rental.</p>
    </header>

    <section class="form-card">
        <h2>Are you sure?</h2>

        <p>
            You are about to delete the rental for
            <strong><?php echo htmlspecialchars($rental['dress_name'], ENT_QUOTES, 'UTF-8'); ?></strong>.
        </p>

        <p>This action cannot be undone.</p>

        <!-- confirm before deleting -->
        <form action="core/handleForms.php" method="POST">
            <input type="hidden" name="rental_id" value="<?php echo $rental_id; ?>">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">

            <button type="submit" name="deleteRentalBtn">Yes, Delete Rental</button>

            <!-- cancel, go back -->
            <a class="view-btn" href="viewrentals.php?customer_id=<?php echo $customer_id; ?>">
                Cancel
            </a>
        </form>
    </section>
</div>

</body>
</html>