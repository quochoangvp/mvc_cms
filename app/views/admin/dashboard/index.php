<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2>Bài viết mới đăng</h2>
    <?php if (isset($articles)): ?>
    <div class="list-group">
        <?php foreach ($articles as $article): ?>
        <a href="<?php echo BASE_URL.'article/'.$article['id'].'/'.$article['slug']; ?>" class="list-group-item">
            <h4 class="list-group-item-heading"><?php echo $article['title'] ?></h4>
            <p class="list-group-item-text"><?php echo Helper::limit_to_numwords(html_entity_decode($article['content'])) ?></p>
        </a>
        <?php endforeach ?>
    </div>
    <?php else: ?>
        <div class="text-center">Không có bài viết nào!</div>
    <?php endif ?>
</div>