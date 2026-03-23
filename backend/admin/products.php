<?php
require_once __DIR__ . '/../config.php';

require_admin();

$theme = get_active_theme();
$search = trim($_GET['q'] ?? '');

$message = '';
$message_class = 'success';

if (isset($_GET['msg'])) {
    $messages = [
        'created' => 'Product created successfully.',
        'updated' => 'Product updated successfully.',
    ];

    if (isset($messages[$_GET['msg']])) {
        $message = $messages[$_GET['msg']];
    }
}

try {
    $pdo = get_db();

    if ($search !== '') {
        $stmt = $pdo->prepare("
            SELECT
                p.id,
                p.title,
                p.brand,
                p.base_price,
                p.stock,
                p.image_file,
                p.is_featured,
                p.is_active,
                c.name AS category_name,
                (
                    SELECT COUNT(*)
                    FROM product_options po
                    WHERE po.product_id = p.id
                ) AS option_count
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.title LIKE :search
               OR p.brand LIKE :search_brand
               OR c.name LIKE :search_category
            ORDER BY p.title ASC
        ");
        $stmt->execute([
            ':search' => "%{$search}%",
            ':search_brand' => "%{$search}%",
            ':search_category' => "%{$search}%",
        ]);
        $products = $stmt->fetchAll();
    } else {
        $stmt = $pdo->query("
            SELECT
                p.id,
                p.title,
                p.brand,
                p.base_price,
                p.stock,
                p.image_file,
                p.is_featured,
                p.is_active,
                c.name AS category_name,
                (
                    SELECT COUNT(*)
                    FROM product_options po
                    WHERE po.product_id = p.id
                ) AS option_count
            FROM products p
            JOIN categories c ON c.id = p.category_id
            ORDER BY p.title ASC
        ");
        $products = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    $products = [];
    $message = 'Unable to load products: ' . $e->getMessage();
    $message_class = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Products — <?= h(SITE_NAME) ?></title>
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
                <li><a href="../../pages/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<main>

    <div class="containerIntro">
        <div class="introText">
            <h1>Product Administration</h1>
            <p class="intro">Use this page to manage product records in the catalogue. You can search products, edit their details, and add new items with options.</p>
        </div>

        <?php if ($message !== ''): ?>
            <div class="alertBar <?= h($message_class) ?>"><?= h($message) ?></div>
        <?php endif; ?>

        <div class="adminToolbar">
            <form method="GET" action="products.php" class="adminSearchForm">
                <input
                    type="search"
                    name="q"
                    class="adminSearchInput"
                    placeholder="Search by product, brand, or category..."
                    value="<?= h($search) ?>"
                >
                <button type="submit" class="btnPrimary">Search</button>
                <?php if ($search !== ''): ?>
                    <a href="products.php" class="btnSecondary">Clear</a>
                <?php endif; ?>
            </form>

            <a href="product-form.php" class="btnPrimary">+ Add Product</a>
        </div>

        <p class="pageCount"><?= count($products) ?> product record(s) shown</p>
    </div>

    <div class="featured">
        <h1>Catalogue Records</h1>

        <div class="dataTableWrap">
            <table class="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Options</th>
                        <th>Featured</th>
                        <th>Active</th>
                        <th>Image File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="10">No products found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= (int) $product['id'] ?></td>
                                <td>
                                    <strong><?= h($product['title']) ?></strong><br>
                                    <span><?= h($product['brand']) ?></span>
                                </td>
                                <td><?= h($product['category_name']) ?></td>
                                <td>$<?= number_format((float) $product['base_price'], 2) ?></td>
                                <td><?= (int) $product['stock'] ?></td>
                                <td><?= (int) $product['option_count'] ?></td>
                                <td>
                                    <span class="statusPill <?= (int) $product['is_featured'] === 1 ? 'featured' : 'standard' ?>">
                                        <?= (int) $product['is_featured'] === 1 ? 'Featured' : 'Standard' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="statusPill <?= (int) $product['is_active'] === 1 ? 'active' : 'inactive' ?>">
                                        <?= (int) $product['is_active'] === 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td><span class="inlineCode"><?= h($product['image_file'] ?: 'none') ?></span></td>
                                <td>
                                    <a href="product-form.php?id=<?= (int) $product['id'] ?>" class="btnSmall">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

</body>
</html>