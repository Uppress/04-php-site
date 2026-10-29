<?php
declare(strict_types=1);

session_start();

$pageTitle = 'Messages';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/functions.php';

if (!isset($_SESSION['messages_csrf_token']) || !is_string($_SESSION['messages_csrf_token'])) {
    $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['messages_csrf_token'];
$loginError = '';
$messagesError = '';
$isAuthenticated = ($_SESSION['messages_authenticated'] ?? false) === true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($postedToken) || !hash_equals($csrfToken, $postedToken)) {
        $loginError = 'Your form session expired. Refresh the page and try again.';
    } elseif (isset($_POST['logout'])) {
        unset($_SESSION['messages_authenticated']);
        session_regenerate_id(true);
        $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
        $csrfToken = $_SESSION['messages_csrf_token'];
        $isAuthenticated = false;
    } elseif (!$isAuthenticated) {
        $password = $_POST['password'] ?? '';
        if (is_string($password) && hash_equals($messagesPassword, $password)) {
            session_regenerate_id(true);
            $_SESSION['messages_authenticated'] = true;
            $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
            $csrfToken = $_SESSION['messages_csrf_token'];
            $isAuthenticated = true;
        } else {
            $loginError = 'The password was not correct. Please try again.';
        }
    }
}

$submissions = [];
if ($isAuthenticated) {
    $messagesPath = __DIR__ . '/storage/messages.jsonl';

    if (is_file($messagesPath)) {
        $lines = file($messagesPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            $messagesError = 'We could not read the saved submissions.';
        } else {
            foreach ($lines as $line) {
                try {
                    $submission = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
                } catch (JsonException $exception) {
                    error_log('Skipping invalid contact message JSON: ' . $exception->getMessage());
                    continue;
                }

                if (!is_array($submission)) {
                    continue;
                }

                $submissions[] = $submission;
            }
        }
    }

    usort(
        $submissions,
        static function (array $first, array $second): int {
            $firstTimestamp = isset($first['timestamp']) && is_string($first['timestamp'])
                ? strtotime($first['timestamp'])
                : false;
            $secondTimestamp = isset($second['timestamp']) && is_string($second['timestamp'])
                ? strtotime($second['timestamp'])
                : false;

            return ($secondTimestamp ?: 0) <=> ($firstTimestamp ?: 0);
        }
    );
}

$serviceTitles = [];
foreach ($services as $service) {
    $serviceTitles[$service['slug']] = $service['title'];
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Private area</p>
        <h1>Contact messages</h1>
        <p class="hero-intro">Review submissions sent through the contact form.</p>
    </div>
</section>

<section class="section">
    <div class="container messages-container">
        <?php if (!$isAuthenticated): ?>
            <div class="messages-login">
                <h2>Sign in to view messages</h2>
                <p>This page is intended for local project learning and review.</p>
                <?php if ($loginError !== ''): ?>
                    <div class="form-alert form-alert-error" role="alert"><?= e($loginError) ?></div>
                <?php endif; ?>
                <form class="contact-form" method="post" action="messages.php">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    <button class="button" type="submit">View messages</button>
                </form>
            </div>
        <?php else: ?>
            <?php if ($messagesError !== ''): ?>
                <div class="form-alert form-alert-error" role="alert"><?= e($messagesError) ?></div>
            <?php endif; ?>
            <div class="messages-toolbar">
                <h2>Submissions <span class="count-badge"><?= count($submissions) ?></span></h2>
                <form method="post" action="messages.php">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                    <button class="button button-outline" type="submit" name="logout" value="1">Sign out</button>
                </form>
            </div>
            <?php if ($submissions === []): ?>
                <p class="empty-state">There are no contact submissions yet.</p>
            <?php else: ?>
                <div class="messages-table-wrap">
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th scope="col">Received</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Service</th>
                                <th scope="col">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $submission): ?>
                                <?php
                                $timestamp = isset($submission['timestamp']) && is_string($submission['timestamp'])
                                    ? strtotime($submission['timestamp'])
                                    : false;
                                $serviceSlug = isset($submission['service']) && is_string($submission['service'])
                                    ? $submission['service']
                                    : '';
                                ?>
                                <tr>
                                    <td><?= e($timestamp === false ? 'Unknown' : date('Y-m-d H:i', $timestamp)) ?></td>
                                    <td><?= e(is_string($submission['name'] ?? null) ? $submission['name'] : '') ?></td>
                                    <td><?= e(is_string($submission['email'] ?? null) ? $submission['email'] : '') ?></td>
                                    <td><?= e(is_string($submission['phone'] ?? null) && $submission['phone'] !== '' ? $submission['phone'] : '—') ?></td>
                                    <td><?= e($serviceTitles[$serviceSlug] ?? $serviceSlug) ?></td>
                                    <td class="message-cell"><?= e(is_string($submission['message'] ?? null) ? $submission['message'] : '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
