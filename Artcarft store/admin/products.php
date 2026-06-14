<?php include 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = (float)$_POST['price'];
        $category_id = (int)$_POST['category_id'];
        $featured = isset($_POST['featured']) ? 1 : 0;

        if ($action === 'add') {
            $image = 'default.jpg';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
            }
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, category_id, featured) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $image, $category_id, $featured]);
            $message = '<div class="alert alert-success">Product added successfully!</div>';
        } else {
            $id = (int)$_POST['id'];
            $image = $_POST['existing_image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
            }
            $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, image=?, category_id=?, featured=? WHERE id=?");
            $stmt->execute([$name, $description, $price, $image, $category_id, $featured, $id]);
            $message = '<div class="alert alert-success">Product updated successfully!</div>';
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success">Product deleted!</div>';
    }
}

$products = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-content">
    <h1>Manage Products</h1>
    <?= $message ?>

    <button class="btn" style="width:auto;margin-bottom:20px;" onclick="$('#product-form').toggle();$('#form-title').text('Add Product');$('#product-form form')[0].reset();$('#action').val('add');$('#product_id').val('');">Add New Product</button>

    <div id="product-form" style="display:none;background:#fff;padding:25px;border-radius:10px;margin-bottom:25px;box-shadow:0 3px 15px rgba(0,0,0,0.08);">
        <h3 id="form-title">Add Product</h3>
        <form method="POST" enctype="multipart/form-data" style="max-width:600px;">
            <input type="hidden" name="action" id="action" value="add">
            <input type="hidden" name="id" id="product_id" value="">
            <input type="hidden" name="existing_image" id="existing_image" value="">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" id="p_name" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="p_description"></textarea>
            </div>
            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" step="0.01" name="price" id="p_price" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" id="p_category">
                    <option value="">No Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="featured" id="p_featured"> Featured Product</label>
            </div>
            <button type="submit" class="btn" style="width:auto;">Save</button>
        </form>
    </div>

    <table class="cart-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Featured</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><img src="../uploads/<?= htmlspecialchars($p['image']) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:5px;" onerror="this.src='https://placehold.co/60x60?text=Art'"></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['category_name'] ?? 'N/A') ?></td>
                <td>₹<?= number_format($p['price'], 2) ?></td>
                <td><?= $p['featured'] ? 'Yes' : 'No' ?></td>
                <td>
                    <button class="btn" style="width:auto;padding:5px 15px;font-size:0.85rem;background:#3498db;" onclick="editProduct(<?= $p['id'] ?>, '<?= addslashes($p['name']) ?>', '<?= addslashes($p['description']) ?>', <?= $p['price'] ?>, <?= $p['category_id'] ?? 'null' ?>, <?= $p['featured'] ?>, '<?= $p['image'] ?>')">Edit</button>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" class="btn" style="width:auto;padding:5px 15px;font-size:0.85rem;background:#e74c3c;">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function editProduct(id, name, desc, price, catId, featured, image) {
    $('#form-title').text('Edit Product');
    $('#action').val('edit');
    $('#product_id').val(id);
    $('#p_name').val(name);
    $('#p_description').val(desc);
    $('#p_price').val(price);
    $('#p_category').val(catId);
    $('#p_featured').prop('checked', featured == 1);
    $('#existing_image').val(image);
    $('#product-form').show();
}
</script>

<?php include 'footer.php'; ?>
