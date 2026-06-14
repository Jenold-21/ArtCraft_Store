<?php include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
}

$orders = $pdo->query("
    SELECT o.*, u.username, u.email AS user_email
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-content">
    <h1>Orders</h1>

    <?php if (empty($orders)): ?>
        <p style="color:#888;">No orders yet.</p>
    <?php else: ?>
        <?php foreach ($orders as $order):
            $items = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $items->execute([$order['id']]);
            $orderItems = $items->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="order-card" style="background:#fff;border-radius:10px;padding:20px;margin-bottom:20px;box-shadow:0 3px 15px rgba(0,0,0,0.08);">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:15px;">
                <div>
                    <strong>Order #<?= $order['id'] ?></strong>
                    <span style="color:#888;margin-left:10px;font-size:0.9rem;"><?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></span>
                </div>
                <div>
                    <span class="status-badge status-<?= htmlspecialchars($order['status']) ?>"><?= ucfirst($order['status']) ?></span>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;margin-bottom:15px;font-size:0.95rem;">
                <div><strong>Customer:</strong> <?= htmlspecialchars($order['name']) ?></div>
                <div><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></div>
                <div><strong>Email:</strong> <?= htmlspecialchars($order['user_email'] ?? 'N/A') ?></div>
                <div><strong>Location:</strong> <?= nl2br(htmlspecialchars($order['location'])) ?></div>
                <div><strong>Total:</strong> <span style="color:#e74c3c;font-weight:700;">₹<?= number_format($order['total'], 2) ?></span></div>
            </div>

            <details style="margin-bottom:10px;">
                <summary style="cursor:pointer;color:#3498db;font-weight:600;">View Items (<?= count($orderItems) ?>)</summary>
                <table class="cart-table" style="margin-top:10px;">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </details>

            <form method="POST" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                <select name="status" style="padding:5px 10px;border:1px solid #ddd;border-radius:4px;">
                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit" name="update_status" class="btn" style="width:auto;padding:5px 15px;font-size:0.85rem;">Update Status</button>
            </form>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.status-badge {
    display:inline-block;
    padding:4px 12px;
    border-radius:20px;
    font-size:0.8rem;
    font-weight:600;
}
.status-pending { background:#ffeaa7; color:#6c5b00; }
.status-processing { background:#81ecec; color:#006666; }
.status-shipped { background:#74b9ff; color:#003366; }
.status-delivered { background:#55efc4; color:#006644; }
.status-cancelled { background:#fab1a0; color:#660000; }
</style>

<?php include 'footer.php'; ?>
