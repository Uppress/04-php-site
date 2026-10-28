<?php
declare(strict_types=1);

$pageTitle = 'Home';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="container hero-content">
        <p class="eyebrow">Print with personality</p>
        <h1>Make a lasting impression.</h1>
        <p class="hero-intro">From the first sketch to the final finish, we help bring your ideas to life with print that feels unmistakably yours.</p>
        <a class="button button-light" href="services.php">Explore our services</a>
    </div>
</section>

<section class="section">
    <div class="container intro-grid">
        <div>
            <p class="eyebrow">A better kind of print partner</p>
            <h2>Good ideas deserve great print.</h2>
        </div>
        <div class="section-copy">
            <p>Uniques Printing Press Ltd. combines considered design, dependable production, and a personal touch to make printed pieces people want to pick up and keep.</p>
            <a class="text-link" href="about.php">Get to know us <span aria-hidden="true">&rarr;</span></a>
        </div>
    </div>
</section>

<section class="section section-tint">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">What we do</p>
            <h2>Print for every next step.</h2>
        </div>
        <div class="card-grid">
            <article class="service-card">
                <span class="card-number">01</span>
                <h3>Business essentials</h3>
                <p>Business cards, stationery, and everyday materials that make your brand feel consistent.</p>
            </article>
            <article class="service-card">
                <span class="card-number">02</span>
                <h3>Marketing print</h3>
                <p>Flyers, brochures, and posters made to share your message clearly and confidently.</p>
            </article>
            <article class="service-card">
                <span class="card-number">03</span>
                <h3>Custom projects</h3>
                <p>Special formats and finishing details for projects that call for something different.</p>
            </article>
        </div>
        <a class="text-link" href="services.php">See all services <span aria-hidden="true">&rarr;</span></a>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
