<?php
require_once 'core/models.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// check kung may valid customer id
if (!isset($_GET['customer_id']) || !is_numeric($_GET['customer_id'])) {
    header("Location: index.php");
    exit();
}

// get info ng customer
$customer_id = (int) $_GET['customer_id'];
$customer = getCustomerByID($pdo, $customer_id);

// balik sa list kung walang nahanap
if (!$customer) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Customer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Delete Customer</h1>
        <p>Please confirm before deleting this customer.</p>
    </header>

    <section class="form-card">
        <h2>Are you sure?</h2>

        <p>
            You are about to delete
            <strong><?php echo htmlspecialchars($customer['customer_name'], ENT_QUOTES, 'UTF-8'); ?></strong>.
        </p>

        <p>
            This will also delete all rental records belonging to this customer.
        </p>

        <!-- confirm before deleting -->
        <form action="core/handleForms.php" method="POST">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">

            <button type="submit" name="deleteCustomerBtn">Yes, Delete Customer</button>

            <!-- cancel, go back -->
            <a class="view-btn" href="index.php">Cancel</a>
        </form>
    </section>
</div>

</body>
</html>