<?php
declare(strict_types=1);

$pageTitle = 'About';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">About us</p>
        <h1>People first. Print always.</h1>
        <p class="hero-intro">We believe the best print starts with listening and ends with something worth sharing.</p>
    </div>
</section>

<section class="section">
    <div class="container story-grid">
        <div>
            <p class="eyebrow">Our approach</p>
            <h2>Made with care, from idea to delivery.</h2>
        </div>
        <div class="section-copy">
            <p>Uniques Printing Press Ltd. is a fictional, independent print studio built around a simple idea: thoughtful service and careful craftsmanship can make print more meaningful.</p>
            <p>We work alongside our customers to understand what they need, recommend the right approach, and give every detail the attention it deserves.</p>
        </div>
    </div>
</section>

<section class="section section-tint">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Meet the team</p>
            <h2>Good people behind good print.</h2>
        </div>
        <div class="team-grid">
            <?php foreach ($team as $member): ?>
                <article class="team-card">
                    <h3><?= e($member['name']) ?></h3>
                    <p class="team-role"><?= e($member['role']) ?></p>
                    <p><?= e($member['bio']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
