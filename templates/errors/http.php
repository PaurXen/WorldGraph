<?php

declare(strict_types=1);

?>
        <section class="error-page container" aria-labelledby="error-title">
            <p class="eyebrow error-code">Błąd <?= htmlspecialchars((string) $data['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <h1 id="error-title"><?= htmlspecialchars($data['heading'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
            <p class="error-message"><?= htmlspecialchars($data['message'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <a class="button" href="/">Powrót do strony głównej <span aria-hidden="true">↗</span></a>
        </section>
