<?php if (count($articles)): ?>
<?php foreach ($articles as $article): ?>
<div class="blog-post">
    <h4 class="blog-post-title"><a href="<?php echo BASE_URL.'article/'.$article['id'].'/'.$article['slug'];?>"><?php echo $article['title'];?></a></h4>
    <p class="blog-post-meta">
        <?php echo date_format(date_create($article['pubdate']), 'F j, Y');?> - <a href="<?php echo BASE_URL.'category/'.$article['cat_id'].'/'.$article['cat_slug'];?>">
            <?php echo $article['cat_title'];?>
        </a>
    </p>

    <p><?php echo Helper::limit_to_numwords(html_entity_decode($article['content']), 50);?></p>
</div>

<?php endforeach?>
<?php echo Helper::pagination('author/'.$user['id'].'/'.$user['name'], $total_articles, $num_per_page, $current_page);?>
<?php else: ?>
    <div class="text-center text-danger">Không có bài viết nào của người dùng này!</div>
<?php endif?>