<?php if (count($page)): ?>
    <div class="page-header">
        <h2><?php echo $page['title'];?></h2>
    </div>
    <div class="page-body">
        <?php echo html_entity_decode($page['content']);?>
    </div>
<?php endif ?>