<?php
declare(strict_types=1);

$pageTitle = 'Service details';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/functions.php';

$slug = filter_input(INPUT_GET, 'slug', FILTER_UNSAFE_RAW);
$service = null;

if (is_string($slug)) {
    foreach ($services as $serviceItem) {
        if ($serviceItem['slug'] === $slug) {
            $service = $serviceItem;
            break;
        }
    }
}

$isNotFound = $service === null;

if ($isNotFound) {
    http_response_code(404);
} else {
    $pageTitle = $service['title'];
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow"><?= $isNotFound ? 'Service not found' : 'Our services' ?></p>
        <h1><?= $isNotFound ? 'We couldn’t find that service.' : e($service['title']) ?></h1>
        <p class="hero-intro">
            <?= $isNotFound
                ? 'The service may have moved, or the link may be incorrect. Browse our services to find what you need.'
                : e($service['description']) ?>
        </p>
    </div>
</section>

<section class="section">
    <div class="container service-detail">
        <?php if ($isNotFound): ?>
            <a class="button" href="services.php">Browse all services</a>
        <?php else: ?>
            <article class="service-detail-card">
                <span class="service-icon" aria-hidden="true"><?= e($service['icon']) ?></span>
                <p class="eyebrow">Starting from</p>
                <p class="detail-price"><?= e(formatPrice($service['price'])) ?></p>
                <p><?= e($service['description']) ?></p>
                <a class="button" href="contact.php">Ask us about this service</a>
            </article>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
