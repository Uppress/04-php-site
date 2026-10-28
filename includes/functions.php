<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $amount): string
{
    return '₦' . number_format($amount, 2);
}

function renderServiceCard(array $service): string
{
    $url = 'service.php?slug=' . rawurlencode($service['slug']);

    return '<article class="service-card">'
        . '<span class="service-icon" aria-hidden="true">' . e($service['icon']) . '</span>'
        . '<h2>' . e($service['title']) . '</h2>'
        . '<p>' . e($service['description']) . '</p>'
        . '<p class="service-price">From ' . e(formatPrice($service['price'])) . '</p>'
        . '<a class="text-link" href="' . e($url) . '">View service <span aria-hidden="true">&rarr;</span></a>'
        . '</article>';
}
