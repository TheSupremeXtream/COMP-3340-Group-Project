<?php
require_once __DIR__ . '/../config.php';

require_admin();

$theme = get_active_theme();

$option_types = [
    'color' => 'Color',
    'storage' => 'Storage',
    'connectivity' => 'Connectivity',
    'length' => 'Length',
    'wattage' => 'Wattage',
];

$product = [
    'id' => 0,
    'category_id' => '',
    'title' => '',
    'brand' => '',
    'description' => '',
    'base_price' => '',
    'stock' => '',
    'image_file' => '',
    'is_featured' => 0,
    'is_active' => 1,
];

$product_options = [
    ['option_type' => 'color', 'option_value' => '', 'price_delta' => '0.00'],
    ['option_type' => 'color', 'option_value' => '', 'price_delta' => '0.00'],
    ['option_type' => 'color', 'option_value' => '', 'price_delta' => '0.00'],
];

$errors = [];
$is_edit = false;

try {
    $pdo = get_db();
    $categories = $pdo->query('SELECT id, name FROM categories ORDER BY name ASC')->fetchAll();
} catch (PDOException $e) {
    $categories = [];
    $errors[] = 'Unable to load categories: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $product_id = (int) $_GET['id'];

    if ($product_id > 0) {
        try {
            $pdo = get_db();

            $stmt = $pdo->prepare("
                SELECT
                    id,
                    category_id,
                    title,
                    brand,
                    description,
                    base_price,
                    stock,
                    image_file,
                    is_featured,
                    is_active
                FROM products
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute([':id' => $product_id]);
            $row = $stmt->fetch();

            if ($row) {
                $product = $row;
                $is_edit = true;

                $stmt = $pdo->prepare("
                    SELECT option_type, option_value, price_delta
                    FROM product_options
                    WHERE product_id = :product_id
                    ORDER BY id ASC
                ");
                $stmt->execute([':product_id' => $product_id]);
                $loaded_options = $stmt->fetchAll();

                foreach ($loaded_options as $index => $loaded_option) {
                    if ($index < 3) {
                        $product_options[$index] = [
                            'option_type' => $loaded_option['option_type'],
                            'option_value' => $loaded_option['option_value'],
                            'price_delta' => number_format((float) $loaded_option['price_delta'], 2, '.', ''),
                        ];
                    }
                }
            } else {
                $errors[] = 'Product not found.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Unable to load product: ' . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product['id'] = (int) ($_POST['product_id'] ?? 0);
    $product['category_id'] = trim($_POST['category_id'] ?? '');
    $product['title'] = trim($_POST['title'] ?? '');
    $product['brand'] = trim($_POST['brand'] ?? '');
    $product['description'] = trim($_POST['description'] ?? '');
    $product['base_price'] = trim($_POST['base_price'] ?? '');
    $product['stock'] = trim($_POST['stock'] ?? '');
    $product['image_file'] = trim($_POST['image_file'] ?? '');
    $product['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
    $product['is_active'] = isset($_POST['is_active']) ? 1 : 0;

    $is_edit = $product['id'] > 0;

    $submitted_options = [];

    for ($i = 1; $i <= 3; $i++) {
        $type = trim($_POST['option_type_' . $i] ?? 'color');
        $value = trim($_POST['option_value_' . $i] ?? '');
        $price_delta = trim($_POST['price_delta_' . $i] ?? '0.00');

        $product_options[$i - 1] = [
            'option_type' => $type,
            'option_value' => $value,
            'price_delta' => $price_delta,
        ];

        if ($value === '') {
            continue;
        }

        if (!isset($option_types[$type])) {
            $errors[] = 'Invalid option type selected.';
            continue;
        }

        if (!is_numeric($price_delta)) {
            $errors[] = 'Each option price delta must be numeric.';
            continue;
        }

        $submitted_options[] = [
            'option_type' => $type,
            'option_value' => $value,
            'price_delta' => (float) $price_delta,
        ];
    }

    if ($product['category_id'] === '' || !ctype_digit($product['category_id'])) {
        $errors[] = 'Please select a category.';
    }

    if ($product['title'] === '') {
        $errors[] = 'Product title is required.';
    }

    if ($product['brand'] === '') {
        $errors[] = 'Brand is required.';
    }

    if ($product['base_price'] === '' || !is_numeric($product['base_price']) || (float) $product['base_price'] < 0) {
        $errors[] = 'Base price must be a valid number.';
    }

    if ($product['stock'] === '' || filter_var($product['stock'], FILTER_VALIDATE_INT) === false || (int) $product['stock'] < 0) {
        $errors[] = 'Stock must be a valid whole number.';
    }

    if (count($submitted_options) < 2) {
        $errors[] = 'Please enter at least 2 product options.';
    }

    if (empty($errors)) {
        try {
            $pdo = get_db();
            $pdo->beginTransaction();

            if ($is_edit) {
                $stmt = $pdo->prepare("
                    UPDATE products
                    SET
                        category_id = :category_id,
                        title = :title,
                        brand = :brand,
                        description = :description,
                        base_price = :base_price,
                        stock = :stock,
                        image_file = :image_file,
                        is_featured = :is_featured,
                        is_active = :is_active
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':category_id' => (int) $product['category_id'],
                    ':title' => $product['title'],
                    ':brand' => $product['brand'],
                    ':description' => $product['description'],
                    ':base_price' => (float) $product['base_price'],
                    ':stock' => (int) $product['stock'],
                    ':image_file' => $product['image_file'],
                    ':is_featured' => $product['is_featured'],
                    ':is_active' => $product['is_active'],
                    ':id' => $product['id'],
                ]);

                $product_id = $product['id'];

                $stmt = $pdo->prepare("DELETE FROM product_options WHERE product_id = :product_id");
                $stmt->execute([':product_id' => $product_id]);
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO products (
                        category_id,
                        title,
                        brand,
                        description,
                        base_price,
                        stock,
                        image_file,
                        is_featured,
                        is_active
                    ) VALUES (
                        :category_id,
                        :title,
                        :brand,
                        :description,
                        :base_price,
                        :stock,
                        :image_file,
                        :is_featured,
                        :is_active
                    )
                ");
                $stmt->execute([
                    ':category_id' => (int) $product['category_id'],
                    ':title' => $product['title'],
                    ':brand' => $product['brand'],
                    ':description' => $product['description'],
                    ':base_price' => (float) $product['base_price'],
                    ':stock' => (int) $product['stock'],
                    ':image_file' => $product['image_file'],
                    ':is_featured' => $product['is_featured'],
                    ':is_active' => $product['is_active'],
                ]);

                $product_id = (int) $pdo->lastInsertId();
            }

            $stmt = $pdo->prepare("
                INSERT INTO product_options (
                    product_id,
                    option_type,
                    option_value,
                    price_delta
                ) VALUES (
                    :product_id,
                    :option_type,
                    :option_value,
                    :price_delta
                )
            ");

            foreach ($submitted_options as $option) {
                $stmt->execute([
                    ':product_id' => $product_id,
                    ':option_type' => $option['option_type'],
                    ':option_value' => $option['option_value'],
                    ':price_delta' => $option['price_delta'],
                ]);
            }

            $pdo->commit();

            header('Location: products.php?msg=' . ($is_edit ? 'updated' : 'created'));
            exit;
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errors[] = 'Unable to save product: ' . $e->getMessage();
        }
    }
}

$page_title = $is_edit ? 'Edit Product' : 'Add Product';
$page_intro = $is_edit
    ? 'Update the selected product record and save the changes back to the database.'
    : 'Create a new catalogue item and add at least two options for it.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= h($page_title) ?> — <?= h(SITE_NAME) ?></title>
    <link rel="stylesheet" href="../../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet" href="../../styles/admin-products.css">
</head>
<body class="theme-<?= h($theme) ?>">

<div class="container">
    <div class="navOuter">
        <div class="navInner">
            <a href="../../index.php" class="banner">
                <img src="../../images/logo.png" alt="<?= h(SITE_NAME) ?>" height="60"><?= h(SITE_NAME) ?>
            </a>
            <ul class="navList">
                <li><a href="../../index.php">Home</a></li>
                <li><a href="../monitor.php">Monitor</a></li>
                <li><a href="theme-settings.php">Templates</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="../../pages/adminDocumentation.html">Admin Docs</a></li>
                <li><a href="../../pages/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<main>

    <div class="containerIntro">
        <div class="introText">
            <h1><?= h($page_title) ?></h1>
            <p class="intro"><?= h($page_intro) ?></p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alertBar error">
                <?php foreach ($errors as $error): ?>
                    <div><?= h($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="formActions">
            <a href="../../pages/editingProducts.html" class="btnSecondary">Help</a>
            <a href="../../pages/adminDocumentation.html" class="btnSecondary">Admin Docs</a>
        </div>
    </div>

    <div class="featured">
        <h1>Product Form</h1>

        <form method="POST" action="product-form.php<?= $is_edit ? '?id=' . (int) $product['id'] : '' ?>">
            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">

            <div class="formGrid">
                <div class="formField">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="adminSelect" required>
                        <option value="">Select category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id'] ?>" <?= (string) $product['category_id'] === (string) $category['id'] ? 'selected' : '' ?>>
                                <?= h($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="formField">
                    <label for="title">Product Title</label>
                    <input type="text" name="title" id="title" class="adminInput" value="<?= h($product['title']) ?>" required>
                </div>

                <div class="formField">
                    <label for="brand">Brand</label>
                    <input type="text" name="brand" id="brand" class="adminInput" value="<?= h($product['brand']) ?>" required>
                </div>

                <div class="formField">
                    <label for="base_price">Base Price</label>
                    <input type="number" name="base_price" id="base_price" class="adminInput" min="0" step="0.01" value="<?= h((string) $product['base_price']) ?>" required>
                </div>

                <div class="formField">
                    <label for="stock">Stock</label>
                    <input type="number" name="stock" id="stock" class="adminInput" min="0" step="1" value="<?= h((string) $product['stock']) ?>" required>
                </div>

                <div class="formField">
                    <label for="image_file">Image File</label>
                    <input type="text" name="image_file" id="image_file" class="adminInput" value="<?= h($product['image_file']) ?>" placeholder="example.jpg">
                    <div class="formNote">Enter the image filename already stored in your backend images folder.</div>
                </div>

                <div class="formField full">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="adminTextarea"><?= h($product['description']) ?></textarea>
                </div>

                <div class="formField full">
                    <label>Record Settings</label>
                    <div class="checkboxRow">
                        <label class="checkboxItem">
                            <input type="checkbox" name="is_featured" value="1" <?= (int) $product['is_featured'] === 1 ? 'checked' : '' ?>>
                            Featured Product
                        </label>

                        <label class="checkboxItem">
                            <input type="checkbox" name="is_active" value="1" <?= (int) $product['is_active'] === 1 ? 'checked' : '' ?>>
                            Active Product
                        </label>
                    </div>
                </div>
            </div>

            <div class="optionSection">
                <h2 class="optionTitle">Product Options</h2>
                <p class="formNote">Enter at least 2 options. Option 3 is optional.</p>

                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="optionGrid">
                        <div class="formField">
                            <label for="option_type_<?= $i ?>">Option <?= $i ?> Type</label>
                            <select name="option_type_<?= $i ?>" id="option_type_<?= $i ?>" class="adminSelect">
                                <?php foreach ($option_types as $value => $label): ?>
                                    <option value="<?= h($value) ?>" <?= $product_options[$i - 1]['option_type'] === $value ? 'selected' : '' ?>>
                                        <?= h($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="formField">
                            <label for="option_value_<?= $i ?>">Option <?= $i ?> Value</label>
                            <input
                                type="text"
                                name="option_value_<?= $i ?>"
                                id="option_value_<?= $i ?>"
                                class="adminInput"
                                value="<?= h($product_options[$i - 1]['option_value']) ?>"
                                placeholder="<?= $i <= 2 ? 'Required' : 'Optional' ?>"
                            >
                        </div>

                        <div class="formField">
                            <label for="price_delta_<?= $i ?>">Price Delta</label>
                            <input
                                type="number"
                                name="price_delta_<?= $i ?>"
                                id="price_delta_<?= $i ?>"
                                class="adminInput"
                                step="0.01"
                                value="<?= h((string) $product_options[$i - 1]['price_delta']) ?>"
                            >
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <div class="formActions">
                <button type="submit" class="btnPrimary"><?= $is_edit ? 'Save Changes' : 'Create Product' ?></button>
                <a href="products.php" class="btnSecondary">Cancel</a>
            </div>
        </form>
    </div>

</main>

</body>
</html>