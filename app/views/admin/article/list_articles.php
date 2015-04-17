<div id="message" class="text-center"><?php echo isset($message) ? $message : '';?></div>
<?php if ($articles): ?>
    <table id="articles_list" class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 3%">#</th>
                <th>Tiêu đề</th>
                <th>Nội dung</th>
                <th style="width: 12%">Ngày đăng</th>
                <th style="width: 10%">Thể loại</th>
                <th style="width: 10%">Tác giả</th>
                <th style="width: 10%">Sửa / Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <th scope="row"><?php echo $article['id'];?></th>
                    <td><?php echo $article['title'];?></td>
                    <td><?php echo Helper::limit_to_numwords(html_entity_decode($article['content']), 50);?></td>
                    <td><?php echo $article['pubdate'];?></td>
                    <td><?php echo $article['cat_title'];?></td>
                    <td><?php echo $article['author'];?></td>
                    <td><a href="<?php echo ADM_URL.'article/edit/'.$article['id'];?>">Sửa</a> /
                        <a class="delete" id="<?php echo $article['id'];?>" href="<?php echo ADM_URL.'article/delete/'.$article['id'];?>">Xóa</a></td>
                </tr>
            <?php endforeach?>
        </tbody>
    </table>
<?php else: ?>
    <p class="text-warning">Không có bài viết nào!</p>
<?php endif?>
<script type="text/javascript">
    $(document).ready(function() {

        $('.delete').click(function(event) {
            event.preventDefault();
            var id = $(this).attr('id');
            var cf = confirm('Are you sure?');

            if (cf == true) {
                $.post('<?php echo ADM_URL.'article/delete_ajax'?>', {id : id}, function(data) {
                    $('#show-list').html(data);
                });
            }
        });

        $('#articles_list').dataTable( {
            "order": [[ 1, "asc" ]]
        });

        setTimeout(function () {
            $('#message').slideUp();
        }, 3000);
    });
</script>