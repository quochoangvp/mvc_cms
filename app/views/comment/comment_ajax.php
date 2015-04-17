<?php if (isset($comments) && count($comments)): ?>
    <div class="actionBox">
        <div id="view_message" class="text-center"><?php if (isset($response)): ?>
            <?php echo $response;?>
        <?php endif?></div>
        <ul class="commentList">
        <?php foreach ($comments as $comment): ?>
            <li id="list_<?php echo $comment['id']?>">
            <?php if (Helper::is_admin()): ?>
                <div class="delete"><a href="#" id="<?php echo $comment['id']?>">x</a></div>
            <?php endif?>
                <div class="commenterImage">
                    <img src="<?php echo IMG_URL.'no-avatar.gif';?>" alt="" />
                </div>
                <div class="commentText">
                    <strong><?php echo $comment['author'];?>: </strong>
                    <p class=""><?php echo $comment['content'];?></p>
                    <span class="date sub-text"><?php echo Helper::time_format($comment['date']);?></span>
                </div>
            </li>
        <?php endforeach?>
        </ul>
    </div>


<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($status) && $status == 'success'): ?>
            $('#message').val('');
        <?php endif;?>

        $('.delete a').click(function(event) {
            event.preventDefault();
            var cid = $(this).attr('id');
            cf = confirm('Thao tác này không thể khôi phục, bạn có muốn tiếp tục?');
            if (cf == true) {
                $.post('<?php echo BASE_URL.'admin/comment/delete_ajax';?>', { id : cid }, function(data, textStatus, xhr) {
                    $('#view_message').html(data.msg);
                    $('#list_'+cid).slideUp(function(){
                        $(this).remove();
                    });
                });
            };
        });
    });
</script>
<?php endif?>