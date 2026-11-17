<?php

declare(strict_types=1);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111713">
    <meta name="description" content="<?= htmlspecialchars($data['description'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
    <title><?= htmlspecialchars($data['title'] ?? 'WorldGraph', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' rx='12' fill='%23111713'/%3E%3Cpath d='M11 27 20 11l9 16H11' fill='none' stroke='%23d3edab' stroke-width='2'/%3E%3Cg fill='%23d3edab'%3E%3Ccircle cx='20' cy='11' r='4'/%3E%3Ccircle cx='11' cy='27' r='4'/%3E%3Ccircle cx='29' cy='27' r='4'/%3E%3C/g%3E%3C/svg%3E">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body id="top">
    <a class="skip-link" href="#main">Przejdź do treści</a>

<?php require dirname(__DIR__) . '/partials/header.php'; ?>

    <main id="main">
<?= $content ?>    </main>

<?php require dirname(__DIR__) . '/partials/footer.php'; ?>
</body>
</html>
