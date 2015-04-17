<div id="message" class="text-center"><?php echo isset($message) ? $message : '';?></div>
<?php if ($categories): ?>
    <table id="categories_list" class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 3%">#</th>
                <th>Tiêu đề</th>
                <th style="width: 15%">Trang trước</th>
                <th style="width: 10%">Sửa / Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <th scope="row"><?php echo $category['id'];?></th>
                    <td><?php echo $category['title'];?></td>
                    <td><?php echo $category['parent_slug'];?></td>
                    <td><a href="<?php echo ADM_URL.'category/edit/'.$category['id'];?>">Sửa</a> /
                        <a class="delete" id="<?php echo $category['id'];?>" href="<?php echo ADM_URL.'category/delete/'.$category['id'];?>">Xóa</a></td>
                </tr>
            <?php endforeach?>
        </tbody>
    </table>
<?php else: ?>
    <p class="text-warning">Không có thể loại nào!</p>
<?php endif?>
<script type="text/javascript">
    $(document).ready(function() {

        $('.delete').click(function(event) {
            event.preventDefault();
            var cid = $(this).attr('id');
            var cf = confirm('Are you sure?');

            if (cf == true) {
                $.post('<?php echo ADM_URL.'category/delete_ajax'?>', {id : cid}, function(data) {
                    $('#show-list').html(data);
                });
            }
        });

        $('#categories_list').dataTable( {
            "order": [[ 1, "asc" ]]
        } );

        setTimeout(function () {
            $('#message').slideUp();
        }, 3000);
    });
</script>