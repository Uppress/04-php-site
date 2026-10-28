<?php
declare(strict_types=1);

$pageTitle = 'Services';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Our services</p>
        <h1>From everyday print to one-of-a-kind.</h1>
        <p class="hero-intro">Practical, polished print solutions shaped around your project.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="card-grid service-card-grid">
            <?php foreach ($services as $service): ?>
                <?= renderServiceCard($service) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-tint">
    <div class="container callout">
        <div>
            <p class="eyebrow">Have a project in mind?</p>
            <h2>Let’s make something memorable.</h2>
        </div>
        <a class="button" href="contact.php">Talk to our team</a>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
