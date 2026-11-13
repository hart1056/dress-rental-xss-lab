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

// get yung customer
$customer_id = (int) $_GET['customer_id'];
$customer = getCustomerByID($pdo, $customer_id);

// balik sa list kung walang nahanap
if (!$customer) {
    header("Location: index.php");
    exit();
}

// get lahat ng rental records ng customer
$rentals = getRentalsByCustomer($pdo, $customer_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Rentals</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Customer Rentals</h1>
        <p>
            Rental records for
            <?php echo htmlspecialchars($customer['customer_name'], ENT_QUOTES, 'UTF-8'); ?>
        </p>
    </header>

    <section class="form-card">
        <h2>Add Rental Record</h2>

        <form action="core/handleForms.php" method="POST">
            <input type="hidden" name="customer_id"
                   value="<?php echo $customer_id; ?>">

            <label for="dress_name">Dress Name</label>
            <input type="text" id="dress_name" name="dress_name" required>

            <label for="rental_date">Rental Date</label>
            <input type="date" id="rental_date" name="rental_date" required>

            <label for="return_date">Return Date</label>
            <input type="date" id="return_date" name="return_date" required>

            <label for="rental_fee">Rental Fee (₱)</label>
            <input type="number" id="rental_fee" name="rental_fee"
                   min="0" step="0.01" required>

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Rented">Rented</option>
                <option value="Returned">Returned</option>
                <option value="Overdue">Overdue</option>
            </select>

            <button type="submit" name="insertRentalBtn">Add Rental</button>
        </form>
    </section>

    <section class="table-card">
        <h2>Rental Records</h2>

        <?php if (count($rentals) > 0) { ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Rental ID</th>
                            <th>Dress Name</th>
                            <th>Rental Date</th>
                            <th>Return Date</th>
                            <th>Rental Fee</th>
                            <th>Status</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($rentals as $rental) { ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($rental['rental_id'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($rental['dress_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($rental['rental_date'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($rental['return_date'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    ₱<?php echo number_format((float) $rental['rental_fee'], 2); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($rental['status'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $rental['added_by_username'] ?? 'Unknown',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $rental['last_updated_by_username'] ?? 'Not updated',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $rental['last_updated'] ?? 'Not updated',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </td>

                                <td class="actions">
                                    <a class="edit-btn"
                                       href="editrental.php?rental_id=<?php echo urlencode($rental['rental_id']); ?>">
                                        Edit
                                    </a>

                                    <a class="delete-btn"
                                       href="deleterental.php?rental_id=<?php echo urlencode($rental['rental_id']); ?>&customer_id=<?php echo urlencode($customer_id); ?>">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        <?php } else { ?>

            <p class="empty-message">
                No rental records yet. Add the customer's first rental above.
            </p>

        <?php } ?>
    </section>

    <p>
        <a class="view-btn" href="index.php">← Back to Customer List</a>
    </p>
</div>

</body>
</html>