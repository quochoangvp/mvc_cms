<div id="message" class="text-center"><?php echo isset($message) ? $message : '';?></div>
<?php if ($users): ?>
    <table id="users_list" class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 3%">#</th>
                <th>Username</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Chức vụ</th>
                <th style="width: 10%">Sửa / Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <th scope="row"><?php echo $user['id'];?></th>
                    <td><?php echo $user['name'];?></td>
                    <td><?php echo $user['email'];?></td>
                    <td><?php echo $user['fullname'];?></td>
                    <td><?php echo Helper::level($user['level']);?></td>
                    <td><a href="<?php echo ADM_URL.'user/edit/'.$user['id'];?>">Sửa</a> / <a class="delete" id="<?php echo $user['id'];?>" href="<?php echo ADM_URL.'user/delete/'.$user['id'];?>">Xóa</a></td>
                </tr>
            <?php endforeach?>
        </tbody>
    </table>
<script type="text/javascript">
    $(document).ready(function() {

        $('.delete').click(function(event) {
            event.preventDefault();
            var uid = $(this).attr('id');
            var cf = confirm('Are you sure?');

            if (cf == true) {
                $.post('<?php echo ADM_URL.'user/delete_ajax'?>', {id : uid}, function(data) {
                    $('#show-list').html(data);
                });
            }
        });

        $('#users_list').dataTable( {
            "order": [[ 1, "asc" ]]
        } );

        setTimeout(function () {
            $('#message').slideUp();
        }, 3000);
    });
</script>
<?php else: ?>
    <p class="text-warning">Không có người dùng nào!</p>
<?php endif?>