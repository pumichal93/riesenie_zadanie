<?php
$this->Paginator->setTemplates([
    'number' => '<li><a class = "pages" href="#">{{text}}</a></li>'
]);
$this->Paginator->options(['paging' => $pagingParams]);
?>
<nav>
    <ul class="pagination">
        <?= $this->Paginator->numbers([
        'after' => '</li>',
        'before' => '<li class="pagination">'
        ]) ?>
    </ul>

    <p><?= $this->Paginator->counter() ?></p>
</nav>