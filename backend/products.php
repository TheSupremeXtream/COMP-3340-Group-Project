<?php
require_once __DIR__ . '/config.php';

$search   = trim($_GET['q'] ?? '');
$cat_slug = trim($_GET['cat'] ?? '');
$sort     = trim($_GET['sort'] ?? 'title');
$page     = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 10;

$allowed_sorts = [
    'title'      => 'p.title ASC',
    'price_asc'  => 'p.base_price ASC',
    'price_desc' => 'p.base_price DESC',
];
$order_sql = $allowed_sorts[$sort] ?? $allowed_sorts['title'];

$where_parts = ['p.is_active = 1'];
$bind_params = [];

if ($search !== '') {
    $where_parts[] = '(p.title LIKE :search OR p.brand LIKE :search2)';
    $bind_params[':search'] = "%{$search}%";
    $bind_params[':search2'] = "%{$search}%";
}

if ($cat_slug !== '') {
    $where_parts[] = 'c.slug = :cat_slug';
    $bind_params[':cat_slug'] = $cat_slug;
}

$where_sql = 'WHERE ' . implode(' AND ', $where_parts);

try {
    $pdo = get_db();

    $count_stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM products p
        JOIN categories c ON c.id = p.category_id
        {$where_sql}
    ");
    $count_stmt->execute($bind_params);

    $total_rows = (int) $count_stmt->fetchColumn();
    $total_pages = max(1, (int) ceil($total_rows / $per_page));
    $page = min($page, $total_pages);
    $offset = ($page - 1) * $per_page;

    $stmt = $pdo->prepare("
        SELECT
            p.id,
            p.title,
            p.brand,
            p.description,
            p.base_price,
            p.stock,
            p.image_file,
            p.is_featured,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        JOIN categories c ON c.id = p.category_id
        {$where_sql}
        ORDER BY {$order_sql}
        LIMIT :limit OFFSET :offset
    ");

    foreach ($bind_params as $key => $val) {
        $stmt->bindValue($key, $val);
    }

    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $products = $stmt->fetchAll();
    $categories = $pdo->query('SELECT id, name, slug FROM categories ORDER BY name ASC')->fetchAll();
    $db_error = null;
} catch (PDOException $e) {
    $products = [];
    $categories = [];
    $total_rows = 0;
    $total_pages = 1;
    $db_error = 'Unable to load products: ' . $e->getMessage();
}

$theme = get_active_theme();
$cart_image = get_theme_cart_image();

$query_parts = [];
if ($search !== '') {
    $query_parts['q'] = $search;
}
if ($cat_slug !== '') {
    $query_parts['cat'] = $cat_slug;
}
if ($sort !== 'title') {
    $query_parts['sort'] = $sort;
}
$query_string = http_build_query($query_parts);
$page_suffix = $query_string !== '' ? '&' . $query_string : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $search !== '' ? 'Search: ' . h($search) . ' — ' : '' ?>Products — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Browse our catalogue of computer accessories, cables, storage, audio, and more.">
    <meta name="keywords" content="computer accessories, cables, USB, storage, headphones, gaming, tech store">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= h(BASE_URL) ?>backend/products.php">
    <link rel="stylesheet" href="../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet" href="../styles/products.css">
</head>
<body class="theme-<?= h($theme) ?>">

    <div class="container">
        <div class="navOuter">
            <div class="navInner">
                <a href="../index.php" class="banner">
                    <img src="../images/logo.png" alt="<?= h(SITE_NAME) ?>" height="60"><?= h(SITE_NAME) ?>
                </a>

                <ul class="navList">
                    <li><a href="products.php">Products</a></li>
                    <li><a href="../pages/About.php">About</a></li>
                    <li><a href="../pages/contactUs.php">Contact Us</a></li>
                    <li><a href="../pages/Wiki.html">Wiki</a></li>
                </ul>

                <ul class="login">
                    <li class="divider"></li>

                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <li><a href="../backend/admin/products.php">Admin</a></li>
                        <?php endif; ?>
                        <li><a href="../pages/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="../pages/login.php">Login</a></li>
                        <li><a href="../pages/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>

                <ul class="cart">
                    <li class="divider"></li>
                    <li>
                        <a href="../cart.html">
                            <img src="../images/<?= h($cart_image) ?>" alt="Cart" height="60">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <main class="catalogue-main">

        <div class="catalogue-heading">
            <h1>Our Product Catalogue</h1>
            <p><?= $total_rows ?> item<?= $total_rows !== 1 ? 's' : '' ?> found</p>
        </div>

        <?php if ($db_error): ?>
            <div class="alert-error">⚠️ <?= h($db_error) ?></div>
        <?php endif; ?>

        <form method="GET" action="products.php" class="filters-form">
            <label for="q" class="sr-only">Search products</label>
            <input
                type="search"
                id="q"
                name="q"
                placeholder="Search name or brand…"
                value="<?= h($search) ?>"
                class="filter-search"
            >

            <label for="cat" class="sr-only">Category</label>
            <select id="cat" name="cat" class="filter-select">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= h($cat['slug']) ?>" <?= $cat['slug'] === $cat_slug ? 'selected' : '' ?>>
                        <?= h($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="sort" class="sr-only">Sort by</label>
            <select id="sort" name="sort" class="filter-select">
                <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>Name A–Z</option>
                <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price ↑</option>
                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price ↓</option>
            </select>

            <button type="submit" class="btn-search">Search</button>

            <?php if ($search !== '' || $cat_slug !== '' || $sort !== 'title'): ?>
                <a href="products.php" class="btn-clear">✕ Clear</a>
            <?php endif; ?>
        </form>

        <?php if (empty($products) && !$db_error): ?>
            <div class="no-results">
                <p>😕 No products match your search. <a href="products.php">Browse all products</a>.</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $p): ?>
                    <?php $product_image = product_image_filename($p['image_file']); ?>
                    <article class="product-card <?= (int) $p['is_featured'] === 1 ? 'featured' : '' ?>">

                        <?php if ((int) $p['is_featured'] === 1): ?>
                            <span class="badge-featured">⭐ Featured</span>
                        <?php endif; ?>

                        <a href="product-detail.php?id=<?= (int) $p['id'] ?>" class="product-media">
                            <img
                                src="images/<?= h($product_image) ?>"
                                alt="<?= h($p['title']) ?>"
                                class="product-img"
                                loading="lazy"
                            >
                        </a>

                        <div class="product-info">
                            <span class="product-category"><?= h($p['category_name']) ?></span>

                            <h2 class="product-title">
                                <a href="product-detail.php?id=<?= (int) $p['id'] ?>"><?= h($p['title']) ?></a>
                            </h2>

                            <p class="product-brand">by <?= h($p['brand']) ?></p>
                            <p class="product-desc"><?= h(mb_strimwidth((string) $p['description'], 0, 110, '…')) ?></p>

                            <div class="product-footer">
                                <span class="product-price">$<?= number_format((float) $p['base_price'], 2) ?></span>

                                <?php if ((int) $p['stock'] > 0): ?>
                                    <span class="in-stock">In Stock</span>
                                <?php else: ?>
                                    <span class="out-of-stock">Out of Stock</span>
                                <?php endif; ?>
                            </div>

                            <a href="product-detail.php?id=<?= (int) $p['id'] ?>" class="btn-view">View Details</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($total_pages > 1): ?>
                <nav class="pagination" aria-label="Product pages">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 . $page_suffix ?>" class="page-btn">&laquo; Prev</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i . $page_suffix ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 . $page_suffix ?>" class="page-btn">Next &raquo;</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>

    </main>

    <footer class="site-footer">
        <p>&copy; <?= date('Y') ?> <?= h(SITE_NAME) ?> — All rights reserved.</p>
    </footer>

</body>
</html>