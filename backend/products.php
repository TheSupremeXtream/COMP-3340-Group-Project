<?php
require_once __DIR__ . '/config.php';
<<<<<<< HEAD

=======
<<<<<<< HEAD

$search   = trim($_GET['q'] ?? '');
$cat_slug = trim($_GET['cat'] ?? '');
=======
 
>>>>>>> 5897e1a5e2b6222e46182b297f047d016a595ad0
$search   = trim($_GET['q']    ?? '');
$cat_slug = trim($_GET['cat']  ?? '');
>>>>>>> 818c04bfbf4f77336a310c957c51bcef6fd6152f
$sort     = trim($_GET['sort'] ?? 'title');
$page     = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 9;

$allowed_sorts = [
    'title'      => 'p.title ASC',
    'price_asc'  => 'p.base_price ASC',
    'price_desc' => 'p.base_price DESC',
];
$order_sql = $allowed_sorts[$sort] ?? $allowed_sorts['title'];

$where_parts = ['p.is_active = 1'];
$bind_params = [];

if ($search !== '') {
    $where_parts[]           = '(p.title LIKE :search OR p.brand LIKE :search2)';
    $bind_params[':search']  = "%{$search}%";
    $bind_params[':search2'] = "%{$search}%";
}

if ($cat_slug !== '') {
    $where_parts[]            = 'c.slug = :cat_slug';
    $bind_params[':cat_slug'] = $cat_slug;
}

$where_sql = 'WHERE ' . implode(' AND ', $where_parts);

try {
    $pdo = get_db();
<<<<<<< HEAD

=======
<<<<<<< HEAD

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM products p
        JOIN categories c ON c.id = p.category_id
        {$where_sql}
    ");
=======
 
>>>>>>> 5897e1a5e2b6222e46182b297f047d016a595ad0
    $stmt       = $pdo->prepare("SELECT COUNT(*) FROM products p JOIN categories c ON c.id = p.category_id {$where_sql}");
>>>>>>> 818c04bfbf4f77336a310c957c51bcef6fd6152f
    $stmt->execute($bind_params);
    $total_rows  = (int) $stmt->fetchColumn();
    $total_pages = max(1, (int) ceil($total_rows / $per_page));
    $page        = min($page, $total_pages);
    $offset      = ($page - 1) * $per_page;

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
<<<<<<< HEAD

    $categories = $pdo->query('SELECT id, name, slug FROM categories ORDER BY name')->fetchAll();
    $db_error   = null;

=======
<<<<<<< HEAD

    $categories = $pdo->query('SELECT id, name, slug FROM categories ORDER BY name ASC')->fetchAll();
    $db_error = null;
=======
 
    $categories = $pdo->query('SELECT id, name, slug FROM categories ORDER BY name')->fetchAll();
    $db_error   = null;
 
>>>>>>> 818c04bfbf4f77336a310c957c51bcef6fd6152f
>>>>>>> 5897e1a5e2b6222e46182b297f047d016a595ad0
} catch (PDOException $e) {
    $products    = [];
    $categories  = [];
    $total_rows  = 0;
    $total_pages = 1;
    $db_error    = 'Unable to load products: ' . $e->getMessage();
}

$theme = get_active_theme();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $search !== '' ? 'Search: ' . h($search) . ' — ' : '' ?>Products — The Computer Store</title>
    <meta name="description" content="Browse our catalogue of computer accessories, cables, storage, audio, and more.">
<<<<<<< HEAD
    <meta name="keywords" content="computer accessories, cables, USB, storage, headphones, gaming, tech store">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet" href="../styles/products.css">
</head>
<body class="theme-<?= h($theme) ?>">

<div class="container">
    <div class="navOuter">
        <div class="navInner">
            <a href="../index.php" class="banner">
                <img src="../images/logo.png" alt="The Computer Store" height="60">The Computer Store
            </a>
            <ul class="navList">
                <li><a href="../index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="../pages/About.html">About</a></li>
                <li><a href="../pages/Help.html">Help</a></li>
                <li><a href="../pages/Wiki.html">Wiki</a></li>
            </ul>
        </div>
    </div>
</div>

=======
    <meta name="keywords"    content="computer accessories, cables, USB, storage, headphones, gaming, tech store">
    <meta name="robots"      content="index, follow">
    <link rel="canonical"    href="<?= h(BASE_URL) ?>products.php">
    <link rel="stylesheet"   href="../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet"   href="assets/css/products.css">
</head>
<body>
<<<<<<< HEAD

<header class="site-header">
    <div class="header-inner">
        <a href="../index.php" class="site-brand">
            <img src="../images/logo.png" alt="<?= h(SITE_NAME) ?>" height="40">
            <?= h(SITE_NAME) ?>
        </a>
        <nav class="site-nav">
            <a href="../index.php">Home</a>
            <a href="products.php" class="active">Products</a>
            <a href="../pages/About.html">About</a>
            <a href="../pages/Help.html">Help</a>
            <a href="../pages/Wiki.html">Wiki</a>
        </nav>
    </div>
</header>

=======
 
<?php include __DIR__ . '/includes/header.php'; ?>
 
>>>>>>> 818c04bfbf4f77336a310c957c51bcef6fd6152f
>>>>>>> 5897e1a5e2b6222e46182b297f047d016a595ad0
<main class="catalogue-main">

    <div class="catalogue-heading">
        <h1>Our Product Catalogue</h1>
        <p><?= $total_rows ?> item<?= $total_rows !== 1 ? 's' : '' ?> found</p>
    </div>

    <?php if ($db_error): ?>
        <div class="alert alert-error">⚠️ <?= h($db_error) ?></div>
    <?php endif; ?>

    <form method="GET" action="products.php" class="filters-form">
        <label for="q" class="sr-only">Search products</label>
        <input type="search" id="q" name="q" placeholder="Search name or brand…" value="<?= h($search) ?>" class="filter-search">

        <label for="cat" class="sr-only">Category</label>
        <select id="cat" name="cat" class="filter-select" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= h($cat['slug']) ?>" <?= $cat['slug'] === $cat_slug ? 'selected' : '' ?>>
                    <?= h($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="sort" class="sr-only">Sort by</label>
        <select id="sort" name="sort" class="filter-select" onchange="this.form.submit()">
            <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>Name A–Z</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price ↑</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price ↓</option>
        </select>

        <button type="submit" class="btn-search">Search</button>

        <?php if ($search !== '' || $cat_slug !== ''): ?>
            <a href="products.php" class="btn-clear">✕ Clear</a>
        <?php endif; ?>
    </form>

    <?php if (empty($products) && !$db_error): ?>
        <div class="no-results">
            <p>😕 No products match your search. <a href="products.php">Browse all products</a>.</p>
        </div>
    <?php else: ?>
<<<<<<< HEAD
    <div class="products-grid">
        <?php foreach ($products as $p): ?>
        <article class="product-card <?= $p['is_featured'] ? 'featured' : '' ?>">

            <?php if ($p['is_featured']): ?>
            <span class="badge-featured">⭐ Featured</span>
            <?php endif; ?>

            <a href="product-detail.php?id=<?= (int) $p['id'] ?>">
                <img src="images/<?= h($p['image_file'] ?? 'placeholder.jpg') ?>"
                     alt="<?= h($p['title']) ?>"
                     class="product-img"
                     loading="lazy"
                     onerror="this.src='images/placeholder.jpg'">
            </a>

            <div class="product-info">
                <span class="product-category"><?= h($p['category_name']) ?></span>
                <h2 class="product-title">
                    <a href="product-detail.php?id=<?= (int) $p['id'] ?>"><?= h($p['title']) ?></a>
                </h2>
                <p class="product-author">by <?= h($p['brand']) ?></p>
                <p class="product-desc"><?= h(mb_strimwidth($p['description'], 0, 100, '…')) ?></p>
                <div class="product-footer">
                    <span class="product-price">$<?= number_format((float) $p['base_price'], 2) ?></span>
                    <?php if ($p['stock'] > 0): ?>
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

    <?php if ($total_pages > 1):
        $q_parts = array_filter(['q' => $search, 'cat' => $cat_slug, 'sort' => $sort !== 'title' ? $sort : '']);
        $qs = ($qs = http_build_query($q_parts)) !== '' ? '&' . $qs : '';
    ?>
    <nav class="pagination" aria-label="Product pages">
        <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 . $qs ?>" class="page-btn">&laquo; Prev</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i . $qs ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 . $qs ?>" class="page-btn">Next &raquo;</a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>

=======
        <div class="products-grid">
            <?php foreach ($products as $p): ?>
                <article class="product-card <?= $p['is_featured'] ? 'featured' : '' ?>">
                    <?php if ($p['is_featured']): ?>
                        <span class="badge-featured">⭐ Featured</span>
                    <?php endif; ?>

                    <a href="product-detail.php?id=<?= (int) $p['id'] ?>">
                        <img
                            src="images/<?= h($p['image_file'] ?? '') ?>"
                            alt="<?= h($p['title']) ?>"
                            class="product-img"
                            loading="lazy"
                            onerror="this.style.display='none'"
                        >
                    </a>

                    <div class="product-info">
                        <span class="product-category"><?= h($p['category_name']) ?></span>
                        <h2 class="product-title">
                            <a href="product-detail.php?id=<?= (int) $p['id'] ?>"><?= h($p['title']) ?></a>
                        </h2>
                        <p class="product-author">by <?= h($p['brand']) ?></p>
                        <p class="product-desc"><?= h(mb_strimwidth($p['description'], 0, 100, '…')) ?></p>

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

        <?php if ($total_pages > 1):
            $q_parts = [
                'q' => $search,
                'cat' => $cat_slug,
            ];

            if ($sort !== 'title') {
                $q_parts['sort'] = $sort;
            }

            $q_parts = array_filter($q_parts, fn($value) => $value !== '');
            $qs = http_build_query($q_parts);
            $qs = $qs !== '' ? '&' . $qs : '';
        ?>
            <nav class="pagination" aria-label="Product pages">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 . $qs ?>" class="page-btn">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i . $qs ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 . $qs ?>" class="page-btn">Next &raquo;</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
>>>>>>> 5897e1a5e2b6222e46182b297f047d016a595ad0
    <?php endif; ?>

</main>
<<<<<<< HEAD

<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> <?= h(SITE_NAME) ?> — All rights reserved.</p>
</footer>
<script src="assets/js/main.js"></script>
=======
>>>>>>> 5897e1a5e2b6222e46182b297f047d016a595ad0
</body>
</html>