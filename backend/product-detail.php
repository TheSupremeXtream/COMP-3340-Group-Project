<?php
require_once __DIR__ . '/config.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: products.php');
    exit;
}

try {
    $pdo = get_db();

    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug
        FROM products p
        JOIN categories c ON c.id = p.category_id
        WHERE p.id = :id AND p.is_active = 1
    ");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch();

    if (!$product) {
        header('Location: products.php');
        exit;
    }

    $opts_stmt = $pdo->prepare("SELECT * FROM product_options WHERE product_id = :id ORDER BY option_type");
    $opts_stmt->execute([':id' => $id]);
    $all_options = $opts_stmt->fetchAll();

    $options_grouped = [];
    foreach ($all_options as $opt) {
        $options_grouped[$opt['option_type']][] = $opt;
    }

    $related_stmt = $pdo->prepare("
        SELECT p.id, p.title, p.brand, p.base_price, p.image_file
        FROM products p
        WHERE p.category_id = :cat AND p.id != :id AND p.is_active = 1
        LIMIT 4
    ");
    $related_stmt->execute([':cat' => $product['category_id'], ':id' => $id]);
    $related = $related_stmt->fetchAll();

    $db_error = null;
} catch (PDOException $e) {
    $db_error = $e->getMessage();
    $product  = null;
    $options_grouped = [];
    $related  = [];
}

if ($db_error || !$product) {
    header('Location: products.php');
    exit;
}

$theme = get_active_theme();

$option_labels = [
    'storage'       => 'Storage',
    'connectivity'  => 'Connectivity',
    'color'         => 'Color',
    'length'        => 'Length',
    'wattage'       => 'Wattage',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($product['title']) ?> — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="<?= h(mb_strimwidth($product['description'], 0, 160, '…')) ?>">
    <meta name="keywords"    content="<?= h($product['title']) ?>, <?= h($product['brand']) ?>, <?= h($product['category_name']) ?>, buy online">
    <meta name="robots"      content="index, follow">
    <link rel="canonical"    href="<?= h(BASE_URL) ?>backend/product-detail.php?id=<?= $id ?>">
    <link rel="stylesheet"   href="../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet"   href="assets/css/product-detail.css">
</head>
<body>

<div class="container">
    <div class="navOuter">
        <div class="navInner">
            <a href="../index.php" class="banner">
                <img src="../images/logo.png" alt="<?= h(SITE_NAME) ?>" height="60"><?= h(SITE_NAME) ?>
            </a>
            <ul class="navList">
                <li><a href="../index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="../pages/About.html">About</a></li>
                <li><a href="../pages/Help.html">Help</a></li>
                <li><a href="../pages/Wiki.html">Wiki</a></li>
            </ul>
            <ul class="login">
                <li class="divider"></li>
                <li><a href="../pages/login.html">Login</a></li>
                <li><a href="../pages/login.html">Register</a></li>
            </ul>
            <ul class="cart">
                <li class="divider"></li>
                <li><a href="../cart.html">
                    <img src="../images/Light_Mode_Cart.png" alt="Cart" height="60">
                </a></li>
            </ul>
            <ul class="toggle">
                <li class="divider"></li>
                <li><a href="../toggle.php"><img src="../images/Sun.png" alt="Light" height="60"></a></li>
                <li><a href="../toggle.php"><img src="../images/Moon.png" alt="Dark" height="60"></a></li>
                <li><a href="../toggle.php"><img src="../images/Egg.png" alt="Holiday" height="60"></a></li>
            </ul>
        </div>
    </div>
</div>

<main class="detail-main">

    <nav class="breadcrumb">
        <a href="../index.html">Home</a> &rsaquo;
        <a href="products.php">Products</a> &rsaquo;
        <a href="products.php?cat=<?= h($product['category_slug']) ?>"><?= h($product['category_name']) ?></a> &rsaquo;
        <span><?= h($product['title']) ?></span>
    </nav>

    <div class="detail-grid">

        <div class="detail-image-col">
            <div class="main-image-wrap">
                <img id="main-image"
                     src="images/<?= h($product['image_file'] ?? 'placeholder.jpg') ?>"
                     alt="<?= h($product['title']) ?>"
                     onerror="this.src='images/placeholder.jpg'">
            </div>
            <?php if ($product['is_featured']): ?>
            <div class="featured-badge">⭐ Featured Product</div>
            <?php endif; ?>
        </div>

        <div class="detail-info-col">

            <span class="detail-category"><?= h($product['category_name']) ?></span>
            <h1 class="detail-title"><?= h($product['title']) ?></h1>
            <p class="detail-brand">by <strong><?= h($product['brand']) ?></strong></p>
            <p class="detail-desc"><?= h($product['description']) ?></p>

            <div class="detail-price-row">
                <span class="detail-price" id="display-price">$<?= number_format((float)$product['base_price'], 2) ?></span>
                <?php if ($product['stock'] > 0): ?>
                    <span class="stock-badge in-stock">✓ In Stock (<?= (int)$product['stock'] ?> units)</span>
                <?php else: ?>
                    <span class="stock-badge out-of-stock">✗ Out of Stock</span>
                <?php endif; ?>
            </div>

            <form class="options-form" id="options-form">
                <input type="hidden" id="base-price" value="<?= (float)$product['base_price'] ?>">

                <?php foreach ($options_grouped as $type => $opts): ?>
                <div class="option-group">
                    <label class="option-label"><?= h($option_labels[$type] ?? ucfirst($type)) ?>:</label>
                    <div class="option-buttons">
                        <?php foreach ($opts as $i => $opt): ?>
                        <button type="button"
                                class="option-btn <?= $i === 0 ? 'selected' : '' ?>"
                                data-delta="<?= (float)$opt['price_delta'] ?>"
                                data-type="<?= h($type) ?>"
                                onclick="selectOption(this, '<?= h($type) ?>')">
                            <?= h($opt['option_value']) ?>
                            <?php if ($opt['price_delta'] > 0): ?>
                                <span class="opt-delta">+$<?= number_format((float)$opt['price_delta'], 2) ?></span>
                            <?php elseif ($opt['price_delta'] < 0): ?>
                                <span class="opt-delta">-$<?= number_format(abs((float)$opt['price_delta']), 2) ?></span>
                            <?php endif; ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="qty-row">
                    <label class="option-label" for="quantity">Quantity:</label>
                    <div class="qty-controls">
                        <button type="button" onclick="changeQty(-1)">−</button>
                        <input type="number" id="quantity" value="1" min="1" max="<?= (int)$product['stock'] ?>" readonly>
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <div class="total-row">
                    Total: <span id="total-price">$<?= number_format((float)$product['base_price'], 2) ?></span>
                </div>

                <button type="submit" class="btn-add-cart" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                    🛒 Add to Cart
                </button>

                <div id="cart-msg" class="cart-msg" style="display:none;">
                    ✅ Added to cart!
                </div>

            </form>

        </div>
    </div>

    <?php if (!empty($related)): ?>
    <section class="related-section">
        <h2>Related Products</h2>
        <div class="related-grid">
            <?php foreach ($related as $r): ?>
            <a href="product-detail.php?id=<?= (int)$r['id'] ?>" class="related-card">
                <img src="images/<?= h($r['image_file'] ?? 'placeholder.jpg') ?>"
                     alt="<?= h($r['title']) ?>"
                     onerror="this.src='images/placeholder.jpg'">
                <div class="related-info">
                    <span class="related-title"><?= h($r['title']) ?></span>
                    <span class="related-brand"><?= h($r['brand']) ?></span>
                    <span class="related-price">$<?= number_format((float)$r['base_price'], 2) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> <?= h(SITE_NAME) ?> — All rights reserved.</p>
</footer>

<script>
    const basePrice = parseFloat(document.getElementById('base-price').value);
    const selectedDeltas = {};

    function selectOption(btn, type) {
        document.querySelectorAll(`.option-btn[data-type="${type}"]`).forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedDeltas[type] = parseFloat(btn.dataset.delta) || 0;
        updatePrice();
    }

    function updatePrice() {
        const qty   = parseInt(document.getElementById('quantity').value) || 1;
        const delta = Object.values(selectedDeltas).reduce((a, b) => a + b, 0);
        const unit  = basePrice + delta;
        const total = unit * qty;
        document.getElementById('display-price').textContent = '$' + unit.toFixed(2);
        document.getElementById('total-price').textContent   = '$' + total.toFixed(2);
    }

    function changeQty(dir) {
        const input = document.getElementById('quantity');
        const max   = parseInt(input.max) || 99;
        let val     = parseInt(input.value) + dir;
        val         = Math.max(1, Math.min(val, max));
        input.value = val;
        updatePrice();
    }

    document.getElementById('options-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const msg = document.getElementById('cart-msg');
        msg.style.display = 'block';
        setTimeout(() => msg.style.display = 'none', 3000);
    });

    document.querySelectorAll('.option-btn.selected').forEach(btn => {
        selectedDeltas[btn.dataset.type] = parseFloat(btn.dataset.delta) || 0;
    });
    updatePrice();
</script>

</body>
</html>