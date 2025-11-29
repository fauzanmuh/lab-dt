<?php
/**
 * Pagination View Partial
 * 
 * @var Core\Pagination $pagination
 * @var string $baseUrl
 * @var array $queryParams
 */
?>

<div class="mt-4">
    <?= $pagination->render($baseUrl, $queryParams ?? []) ?>
</div>