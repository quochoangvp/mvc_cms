<?php if (isset($udata)): ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="page-header"><?php if (isset($title)) echo $title;?></h2>
    <p id="message-ajax"></p>
    <form action="" method="POST" role="form" id="edit_user_form">

        <div class="form-group">
            <label for="">Username</label>
            <input value="<?php echo $udata['name'];?>" type="text" class="form-control" id="username" name="username" placeholder="Nhập tên người dùng" autofocus="" required="required">
            <p id="username-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Email</label>
            <input value="<?php echo $udata['email'];?>" type="text" class="form-control" id="email" name="email" placeholder="Nhập địa chỉ email" autofocus="" required="required">
            <p id="email-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Họ tên</label>
            <input value="<?php echo $udata['fullname'];?>" type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập họ tên" autofocus="">
        </div>

        <div class="form-group">
            <label for="">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" autofocus="" required="required">
        </div>

        <div class="form-group">
            <label for="">Cấp độ</label>
            <select name="level" id="level" class="form-control" required="required">
                <option value="0" <?php if ($udata['level'] == 0)
                {
                    echo 'selected="selected"';
                }
                ?>><?php echo Helper::level(0);?></option>
                                <option value="1" <?php if ($udata['level'] == 1)
                {
                    echo 'selected="selected"';
                }
                ?>><?php echo Helper::level(1);?></option>
                                <option value="2" <?php if ($udata['level'] == 2)
                {
                    echo 'selected="selected"';
                }
                ?>><?php echo Helper::level(2);?></option>
                                <option value="3" <?php if ($udata['level'] == 3)
                {
                    echo 'selected="selected"';
                }
                ?>><?php echo Helper::level(3);?></option>
            </select>
        </div>
        <input value="<?php echo $udata['id'];?>" type="hidden" id="uid" name="uid">
        <button id="edit_user" type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        var uid = $('#uid').val();

        $('#username').change(function() {

            var username = $(this).val();


            $.post('<?php echo ADM_URL.'user/username_check'?>', { data: { data : username , uid : uid } }, function(data) {
                if (data.status == 'success') {
                    $('#username-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                        .closest('.form-group').removeClass('has-error').addClass('has-success');
                        $('#edit_user').removeAttr('disabled');
                } else{
                    $('#username-msg').html(data.msg)
                    .closest('.form-group').removeClass('has-success').addClass('has-error');
                    $('#edit_user').attr('disabled','disabled');
                };
            });
        });

        $('#email').change(function() {

            var email = $(this).val();

            $.post('<?php echo ADM_URL.'user/email_check'?>', { data: { data : email, uid : uid } }, function(data) {
                if (data.status == 'success') {
                    $('#email-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                        .closest('.form-group').removeClass('has-error').addClass('has-success');
                        $('#edit_user').removeAttr('disabled');
                } else{
                    $('#email-msg').html(data.msg)
                        .closest('.form-group').removeClass('has-success').addClass('has-error');;
                        $('#edit_user').attr('disabled','disabled');
                };
            });
        });

        $('#edit_user').click(function(event) {
            event.preventDefault();

            var username = $('#username').val();
            var email    = $('#email').val();
            var fullname = $('#fullname').val();
            var password = $('#password').val();
            var level    = $('#level').val();

            var bt = $('#edit_user');
            bt.button('loading');

            $.post('<?php echo ADM_URL.'user/edit_submit';?>', {data : { uid : uid, username : username, fullname : fullname, email : email, password : password, level : level }}, function(data) {
                setTimeout(function () {
                    bt.button('reset');
                }, 1500);

                if (data.status == 'success') {
                    $('#message-ajax').slideDown().html(data.msg).delay(3000).slideUp();
                    $('.form-group').removeClass('has-success');
                    $('.form-group').find('span').remove();
                } else {
                    $('#message-ajax').slideDown().html(data.msg).delay(3000).slideUp();
                };
            });
        });
    });
</script>
<?php else: ?>
    <div class="text-center text-warning">Người dùng không tồn tại</div>
<?php endif?>