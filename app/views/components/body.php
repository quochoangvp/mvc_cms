<?php if (isset($path)): ?>
    <?php if (isset($sidebar) && $sidebar == TRUE): ?>
        <div class="col-sm-8 blog-main">
        <?php include_once $path;?>
        </div>
        <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
        <?php include_once 'sidebar.php';?>
        </div>
    <?php else: ?>
        <?php include_once $path;?>
    <?php endif?>
<?php endif ?>