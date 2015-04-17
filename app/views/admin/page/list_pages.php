<div id="message" class="text-center"><?php echo isset($message) ? $message : '';?></div>
<?php if ($pages): ?>
    <table id="pages_list" class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 3%">#</th>
                <th>Tiêu đề</th>
                <th>Nội dung</th>
                <th style="width: 15%">Trang trước</th>
                <th style="width: 10%">Sửa / Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page): ?>
                <tr>
                    <th scope="row"><?php echo $page['id'];?></th>
                    <td><?php echo $page['title'];?></td>
                    <td><?php echo Helper::limit_to_numwords(html_entity_decode($page['content']), 50);?></td>
                    <td><?php echo $page['parent_slug'];?></td>
                    <td><a href="<?php echo ADM_URL.'page/edit/'.$page['id'];?>">Sửa</a> /
                        <a class="delete" id="<?php echo $page['id'];?>" href="<?php echo ADM_URL.'page/delete/'.$page['id'];?>">Xóa</a></td>
                </tr>
            <?php endforeach?>
        </tbody>
    </table>
<script type="text/javascript">
    $(document).ready(function() {

        $('.delete').click(function(event) {
            event.preventDefault();
            var pid = $(this).attr('id');
            var cf = confirm('Are you sure?');

            if (cf == true) {
                $.post('<?php echo ADM_URL.'page/delete_ajax'?>', {id : pid}, function(data) {
                    $('#show-list').html(data);
                });
            }
        });

        $('#pages_list').dataTable( {
            "order": [[ 1, "asc" ]]
        } );

        setTimeout(function () {
            $('#message').slideUp();
        }, 3000);
    });
</script>
<?php else: ?>
    <p class="text-warning">Không có trang nào!</p>
<?php endif?>