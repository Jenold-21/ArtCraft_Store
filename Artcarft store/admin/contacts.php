<?php include 'header.php';

if (isset($_POST['delete'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
}

$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-content">
    <h1>Contact Messages</h1>

    <?php if (empty($contacts)): ?>
        <p style="color:#888;">No messages yet.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $c): ?>
                <tr>
                    <td style="white-space:nowrap;"><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($c['email']) ?>"><?= htmlspecialchars($c['email']) ?></a></td>
                    <td><?= htmlspecialchars($c['phone'] ?: '-') ?></td>
                    <td style="max-width:300px;"><?= nl2br(htmlspecialchars($c['message'])) ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Delete this message?')">
                            <input type="hidden" name="delete" value="1">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <button type="submit" class="btn" style="width:auto;padding:5px 15px;font-size:0.85rem;background:#e74c3c;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
