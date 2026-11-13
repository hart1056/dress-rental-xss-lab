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
    <title>Edit Rental</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Edit Rental Record</h1>
        <p>Update the details of this dress rental.</p>
    </header>

    <section class="form-card">

        <!-- form to update the selected rental -->
        <form action="core/handleForms.php" method="POST">

            <!-- keep ids needed to update correct rental -->
            <input type="hidden" name="rental_id" value="<?php echo $rental_id; ?>">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">

            <label for="dress_name">Dress Name</label>
            <input
                type="text"
                id="dress_name"
                name="dress_name"
                value="<?php echo htmlspecialchars($rental['dress_name'], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

            <label for="rental_date">Rental Date</label>
            <input
                type="date"
                id="rental_date"
                name="rental_date"
                value="<?php echo htmlspecialchars($rental['rental_date'], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

            <label for="return_date">Return Date</label>
            <input
                type="date"
                id="return_date"
                name="return_date"
                value="<?php echo htmlspecialchars($rental['return_date'], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

            <label for="rental_fee">Rental Fee (₱)</label>
            <input
                type="number"
                id="rental_fee"
                name="rental_fee"
                min="0"
                step="0.01"
                value="<?php echo htmlspecialchars($rental['rental_fee'], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Rented" <?php echo $rental['status'] === 'Rented' ? 'selected' : ''; ?>>Rented</option>
                <option value="Returned" <?php echo $rental['status'] === 'Returned' ? 'selected' : ''; ?>>Returned</option>
                <option value="Overdue" <?php echo $rental['status'] === 'Overdue' ? 'selected' : ''; ?>>Overdue</option>
            </select>

            <!-- save updated rental details -->
            <button type="submit" name="updateRentalBtn">Save Changes</button>

            <!-- back to rental list -->
            <a class="view-btn" href="viewrentals.php?customer_id=<?php echo $customer_id; ?>">Cancel</a>
        </form>
    </section>
</div>

</body>
</html>