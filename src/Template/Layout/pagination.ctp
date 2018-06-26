<?php
$this->Paginator->setTemplates([
    'number' => '<li><a href="#">{{text}}</a></li>'
]);
$this->Paginator->options(['paging' => $pagingParams]);
?>
<nav>
    <ul class="pagination">
        <?= $this->Paginator->prev('&laquo; ' . __('previous'), ['escape' => false]) ?>
        <?= $this->Paginator->numbers([
        'after' => '</li>',
        'before' => '<li class="pagination">'
        ]) ?>
        <?= $this->Paginator->next(__('next') . ' &raquo;' , ['escape' => false]) ?>
    </ul>

    <p><?= $this->Paginator->counter() ?></p>
</nav>