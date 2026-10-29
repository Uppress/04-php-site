<?php
declare(strict_types=1);

session_start();

$pageTitle = 'Contact';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/functions.php';

if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];
$errors = [];
$formError = '';
$values = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'service' => '',
    'message' => '',
];
$serviceSlugs = array_column($services, 'slug');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $field => $defaultValue) {
        $postedValue = $_POST[$field] ?? $defaultValue;

        if (!is_string($postedValue)) {
            $errors[$field] = 'Please enter a valid value.';
            continue;
        }

        $values[$field] = trim($postedValue);
    }

    foreach ($values as $field => $value) {
        if (preg_match('//u', $value) !== 1) {
            $errors[$field] = 'Please use valid text.';
        }
    }

    if ($values['name'] === '') {
        $errors['name'] = 'Please enter your name.';
    }

    if ($values['email'] === '') {
        $errors['email'] = 'Please enter your email address.';
    } elseif (filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($values['service'] === '') {
        $errors['service'] = 'Please choose a service.';
    } elseif (!in_array($values['service'], $serviceSlugs, true)) {
        $errors['service'] = 'Please choose a valid service.';
    }

    if ($values['message'] === '') {
        $errors['message'] = 'Please enter a message.';
    } else {
        $messageLength = preg_match_all('/./us', $values['message']);

        if ($messageLength === false || $messageLength < 10 || $messageLength > 1000) {
            $errors['message'] = 'Your message must be between 10 and 1,000 characters.';
        }
    }

    $postedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($postedToken) || !hash_equals($csrfToken, $postedToken)) {
        $errors['_csrf'] = 'Your form session expired. Please try submitting the form again.';
    }

    if ($errors === []) {
        $submission = [
            'timestamp' => date(DATE_ATOM),
            'name' => $values['name'],
            'email' => $values['email'],
            'phone' => $values['phone'],
            'service' => $values['service'],
            'message' => $values['message'],
        ];

        try {
            $storageDirectory = __DIR__ . '/storage';
            if (!is_dir($storageDirectory) && !mkdir($storageDirectory, 0750, true) && !is_dir($storageDirectory)) {
                throw new RuntimeException('Unable to create the contact form storage directory.');
            }

            $jsonLine = json_encode(
                $submission,
                JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR
            );
            $writtenBytes = file_put_contents(
                $storageDirectory . '/messages.jsonl',
                $jsonLine . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );

            if ($writtenBytes === false) {
                throw new RuntimeException('Unable to write the contact form submission.');
            }

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['contact_form_sent'] = true;
            header('Location: contact.php?sent=1');
            exit;
        } catch (RuntimeException | JsonException $exception) {
            error_log('Contact form submission failed: ' . $exception->getMessage());
            http_response_code(500);
            $formError = 'We could not save your message right now. Please try again later or email us directly.';
        }
    }
}

$submissionSent = ($_GET['sent'] ?? '') === '1'
    && ($_SESSION['contact_form_sent'] ?? false) === true;
unset($_SESSION['contact_form_sent']);
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
            <div class="contact-card">
                <span class="contact-label">Email</span>
                <a class="contact-email" href="mailto:<?= e($contactEmail) ?>">
                    <?= e($contactEmail) ?>
                </a>
                <p>Prefer email? Get in touch directly and include your preferred format, quantity, and timing.</p>
            </div>
        </div>
        <div>
            <?php if ($submissionSent): ?>
                <div class="form-alert form-alert-success" role="status">Thanks for reaching out. Your message has been received.</div>
            <?php endif; ?>
            <?php if (isset($errors['_csrf'])): ?>
                <div class="form-alert form-alert-error" role="alert"><?= e($errors['_csrf']) ?></div>
            <?php endif; ?>
            <?php if ($formError !== ''): ?>
                <div class="form-alert form-alert-error" role="alert"><?= e($formError) ?></div>
            <?php endif; ?>

            <form class="contact-form" method="post" action="contact.php">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                <div class="form-field">
                    <label for="name">Name <span aria-hidden="true">*</span></label>
                    <input id="name" name="name" type="text" autocomplete="name" required
                           value="<?= e($values['name']) ?>"
                           <?= isset($errors['name']) ? 'aria-invalid="true" aria-describedby="name-error"' : '' ?>>
                    <?php if (isset($errors['name'])): ?>
                        <p class="field-error" id="name-error"><?= e($errors['name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="email">Email <span aria-hidden="true">*</span></label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="<?= e($values['email']) ?>"
                           <?= isset($errors['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>>
                    <?php if (isset($errors['email'])): ?>
                        <p class="field-error" id="email-error"><?= e($errors['email']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="phone">Phone <span class="optional-label">(optional)</span></label>
                    <input id="phone" name="phone" type="tel" autocomplete="tel"
                           value="<?= e($values['phone']) ?>"
                           <?= isset($errors['phone']) ? 'aria-invalid="true" aria-describedby="phone-error"' : '' ?>>
                    <?php if (isset($errors['phone'])): ?>
                        <p class="field-error" id="phone-error"><?= e($errors['phone']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="service">Service <span aria-hidden="true">*</span></label>
                    <select id="service" name="service" required
                            <?= isset($errors['service']) ? 'aria-invalid="true" aria-describedby="service-error"' : '' ?>>
                        <option value="">Choose a service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= e($service['slug']) ?>"<?= $values['service'] === $service['slug'] ? ' selected' : '' ?>>
                                <?= e($service['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['service'])): ?>
                        <p class="field-error" id="service-error"><?= e($errors['service']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="message">Message <span aria-hidden="true">*</span></label>
                    <textarea id="message" name="message" rows="6" required
                              aria-describedby="<?= isset($errors['message']) ? 'message-hint message-error' : 'message-hint' ?>"
                              <?= isset($errors['message']) ? 'aria-invalid="true"' : '' ?>><?= e($values['message']) ?></textarea>
                    <p class="field-hint" id="message-hint">Please use between 10 and 1,000 characters.</p>
                    <?php if (isset($errors['message'])): ?>
                        <p class="field-error" id="message-error"><?= e($errors['message']) ?></p>
                    <?php endif; ?>
                </div>

                <button class="button" type="submit">Send message</button>
            </form>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
