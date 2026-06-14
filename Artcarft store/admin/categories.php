<?php include 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = trim($_POST['name']);
        if ($name) {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
            $message = '<div class="alert alert-success">Category added!</div>';
        }
    } elseif (isset($_POST['edit'])) {
        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);
        if ($name) {
            $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            $message = '<div class="alert alert-success">Category updated!</div>';
        }
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success">Category deleted!</div>';
    }
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) AS product_count FROM categories c ORDER BY c.name")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-content">
    <h1>Manage Categories</h1>
    <?= $message ?>

    <button class="btn" style="width:auto;margin-bottom:20px;" onclick="$('#cat-form').toggle();$('#cat_action').val('add');$('#cat_id').val('');$('#cat_name').val('');$('#cat-form-title').text('Add Category');">Add New Category</button>

    <div id="cat-form" style="display:none;background:#fff;padding:25px;border-radius:10px;margin-bottom:25px;box-shadow:0 3px 15px rgba(0,0,0,0.08);max-width:500px;">
        <h3 id="cat-form-title">Add Category</h3>
        <form method="POST">
            <input type="hidden" name="id" id="cat_id" value="">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" id="cat_name" required>
            </div>
            <button type="submit" name="add" id="cat_submit" class="btn" style="width:auto;">Save</button>
        </form>
    </div>

    <table class="cart-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td><?= $cat['product_count'] ?></td>
                <td>
                    <button class="btn" style="width:auto;padding:5px 15px;font-size:0.85rem;background:#3498db;" onclick="editCategory(<?= $cat['id'] ?>, '<?= addslashes($cat['name']) ?>')">Edit</button>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this category? Products will become uncategorized.')">
                        <input type="hidden" name="delete" value="1">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <button type="submit" class="btn" style="width:auto;padding:5px 15px;font-size:0.85rem;background:#e74c3c;">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function editCategory(id, name) {
    $('#cat-form-title').text('Edit Category');
    $('#cat_id').val(id);
    $('#cat_name').val(name);
    $('#cat_submit').attr('name', 'edit');
    $('#cat-form').show();
}
</script>

<?php include 'footer.php'; ?>
