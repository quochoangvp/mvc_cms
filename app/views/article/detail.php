<?php if (count($articles)): ?>
<?php foreach ($articles as $article): ?>
    <div class="blog-post">
        <h2 id="<?php echo $article['id'];?>" class="blog-post-title"><?php echo $article['title'];?></h2>
        <p class="blog-post-meta">
            <?php echo date_format(date_create($article['pubdate']), 'F j, Y');?> by
            <a href="<?php echo BASE_URL.'user/profile/'.$article['author_id'];?>">
                <?php echo $article['author'];?>
            </a> - <a href="<?php echo BASE_URL.'category/'.$article['cat_id'].'/'.$article['cat_slug'];?>">
                <?php echo $article['cat_title'];?>
            </a>
        </p>

        <p><?php echo html_entity_decode($article['content']);?></p>
    </div>
<?php endforeach?>
<div id="comment_detail" class="detailBox">
<div class="titleBox">
    <label>Box Bình luận</label>
</div>
<div id="comments"></div>
<div id="comment_form" class="actionBox">
        <form action="" id="commentForm" method="POST" role="form">
            <div class="form-group">
            <?php if ( ! Session::get('uid')): ?>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <label for="fullname">Họ tên</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="" placeholder="Nhập họ tên" />
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <label for="email">Địa chỉ email</label>
                        <input type="text" class="form-control" id="email" name="email" value="" placeholder="Nhập địa chỉ email" />
                    </div>
                </div>

            <?php else: ?>

                <input type="hidden" id="fullname" name="fullname" value="<?php echo Session::get('fullname');?>" />
                <input type="hidden" id="email" name="email" value="<?php echo Session::get('uemail');?>" />

            <?php endif;?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label for="message">Nội dung</label>
                        <textarea name="message" id="message" class="input-lg form-control"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label for="message">Nhập mã bảo mật</label>
                    <div><?php echo $recaptcha;?></div>
                    </div>
                </div>
            </div>

            <button type="submit" id="send" class="btn btn-primary">Gửi bình luận</button>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var aid = $('h2').attr('id');
        $.post('<?php echo BASE_URL.'comment/comment_ajax';?>', { data : {aid : aid} }, function(data, textStatus, xhr) {
            $('#comments').html(data);


            $('#send').click(function(event) {
                event.preventDefault();
                var author = $('#fullname').val();
                var email = $('#email').val();
                var message = $('#message').val();
                var recaptcha_challenge_field = $('#recaptcha_challenge_field').val();
                var recaptcha_response_field = $('#recaptcha_response_field').val();

                if (message.length != 0) {
                    $.post('<?php echo BASE_URL.'comment/comment_ajax';?>', { data : {aid : aid, author: author, email : email, message : message, recaptcha_challenge_field : recaptcha_challenge_field, recaptcha_response_field : recaptcha_response_field } }, function(data, textStatus, xhr) {
                        $('#comments').html(data);
                        Recaptcha.reload();
                    });
                } else {
                    alert('Hãy nhập nội dung!');
                };
            });
        });
    });
</script>
<?php endif ?>