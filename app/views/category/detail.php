<?php if (count($articles)): ?>
<?php foreach ($articles as $article): ?>

<div class="blog-post">
    <h4 class="blog-post-title"><a href="<?php echo BASE_URL.'article/'.$article['id'].'/'.$article['slug'];?>"><?php echo $article['title'];?></a></h4>
    <p class="blog-post-meta">
        <?php echo date_format(date_create($article['pubdate']), 'F j, Y');?> by
        <a href="<?php echo BASE_URL.'user/profile/'.$article['author_id'];?>">
            <?php echo $article['author'];?>
        </a>
    </p>

    <p><?php echo Helper::limit_to_numwords(html_entity_decode($article['content']), 50);?></p>
</div>

<?php endforeach?>
<?php echo Helper::pagination('category/'.$category['id'].'/'.$category['slug'], $total_articles, $num_per_page, $current_page);?>
<?php else: ?>
    <div class="text-center text-danger">Không có bài viết nào trong thể loại này!</div>
<?php endif?>