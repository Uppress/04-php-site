<?php
declare(strict_types=1);

$pageTitle = 'Contact';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Contact us</p>
        <h1>Tell us what you’re making.</h1>
        <p class="hero-intro">Questions, ideas, big plans, small runs—we’d love to hear from you.</p>
    </div>
</section>

<section class="section">
    <div class="container contact-grid">
        <div>
            <p class="eyebrow">Start a conversation</p>
            <h2>Let’s talk print.</h2>
            <p>Send us a note with a little about your project. We’ll be happy to discuss the possibilities.</p>
        </div>
        <div class="contact-card">
            <span class="contact-label">Email</span>
            <a class="contact-email" href="mailto:<?= e($contactEmail) ?>">
                <?= e($contactEmail) ?>
            </a>
            <p>For a quicker conversation, include your preferred format, quantity, and timing.</p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
