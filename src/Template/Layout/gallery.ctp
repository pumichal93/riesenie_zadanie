<?php
/*
 * Layout for gallery render
 */
?>
<div>
    <?= $this->Flash->render() ?>
    <?php foreach ($images as $image): ?>
        <div class="column gallery-img">
            <p><?php echo $image->name ?></p>
            <img src=<?php echo $image->path ?>>
        </div>
    <?php endforeach; ?>
</div>