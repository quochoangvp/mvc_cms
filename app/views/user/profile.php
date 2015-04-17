<?php if (count($udata)): ?>
<div class="container target">
    <div class="page-header">
        <h2><?php echo $udata['name'];?></h2>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <!--left col-->
            <ul class="list-group">
                <?php if ($udata['id'] == Session::get('uid')): ?>
                <li class="list-group-item text-right" contenteditable="false"><span class="pull-left">Thông tin</span>
                    <a class="btn btn-primary btn-xs" data-toggle="modal" href='#edit_profile_modal'>Chỉnh sửa</a>
                    <div class="modal fade text-left" id="edit_profile_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">Sửa thông tin cá nhân</h4>
                                </div>
                                <div class="modal-body">
<form action="" method="POST" role="form" id="edit_profile" name="edit_profile">
    <p id="modal-message" class="text-center"></p>
    <div class="form-group">
        <label for="email">Email</label>
        <input value="<?php echo $udata['email'];?>" type="text" class="form-control" id="email" name="email" placeholder="Địa chỉ email" required="required" autofocus="">
        <p id="email-msg"></p>
    </div>
    <div class="form-group">
        <label for="fullname">Họ tên</label>
        <input value="<?php echo  ! empty($udata['fullname']) ? $udata['fullname'] : '';?>" type="text" class="form-control" id="fullname" name="fullname" placeholder="Họ và tên">
    </div>
</form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" id="profile_submit" form="edit_profile" class="btn btn-primary">Lưu</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    </li>
                <?php else: ?>
                    <li class="list-group-item text-muted" contenteditable="false">Thông tin
                <?php endif?>
                <li class="list-group-item text-right">
                    <span class="pull-left"><strong class="">Đăng ký</strong></span> <?php echo date_format(date_create($udata['regdate']), 'd.m.Y');?>
                </li>
                <li class="list-group-item text-right">
                    <span class="pull-left"><strong class="">Họ tên</strong></span> <?php echo  ! empty($udata['fullname']) ? $udata['fullname'] : 'Đang cập nhật';?>
                </li>
                <li class="list-group-item text-right">
                    <span class="pull-left"><strong class="">Email</strong></span> <?php echo $udata['email'];?>
                </li>
                <li class="list-group-item text-right">
                    <span class="pull-left"><strong class="">Cấp độ</strong></span> <?php echo Helper::level($udata['level']);?>
                </li>
                <?php if ($udata['id'] == Session::get('uid')): ?>
                <li class="list-group-item text-right">
                    <span class="pull-left"><strong class="">Mật khẩu</strong></span> ******
(<a class="" data-toggle="modal" href='#change_password'>Đổi</a>)
<div class="modal fade" id="change_password">
    <div class="modal-dialog">
        <div class="modal-content text-left">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Đổi mật khẩu</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" role="form" id="change_password_form" name="change_password_form">
                    <div class="text-center" id="change_password_msg"></div>
                    <div class="form-group">
                        <label for="">Mật khẩu</label>
                        <input id="cur_pw" type="password" class="form-control" id="" placeholder="Nhập mật khẩu hiện tại" autofocus="" required="required">
                        <p id="cur_pw_msg"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Mật khẩu mới</label>
                        <input id="new_pw" type="password" class="form-control" id="" placeholder="Nhập mật khẩu mới" autofocus="" required="required">
                        <p id="new_pw_msg"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Nhập lại mật khẩu mới</label>
                        <input id="new_pw_cf" type="password" class="form-control" id="" placeholder="Nhập lại mật khẩu mới" autofocus="" required="required">
                        <p id="new_pw_cf_msg"></p>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" id="show_password" value="">Hiện mật khẩu</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button id="change_password_button" type="button" form="change_password_form" class="btn btn-primary">Đổi</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                </li>
                <?php endif;?>
            </ul>
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $udata['name'];?>'s Bio</div>
                <div class="panel-body" id="user_bio">
                    <?php echo $udata['bio'];?>
                    <?php if (Session::get('uid') == $udata['id']): ?>
<div class="pull-right" id="edit_bio_button" style="display: none">
<a class="btn btn-success" data-toggle="modal" href='#edit_bio_modal'>Sửa</a>
<div class="modal fade" id="edit_bio_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Sửa ghi chú bản thân</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" role="form" id="edit_bio" name="edit_bio">
                    <div class="text-center" id="bio-msg"></div>
                    <div class="form-group">
                        <textarea name="bio" id="bio" cols="30" rows="8" class="form-control"><?php echo $udata['bio'];?></textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="save_bio" form="edit_bio" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <!--/col-3-->
        <div class="col-sm-8" contenteditable="false" style="">
        <?php if (isset($articles)): ?>
            <ul class="list-group">
            <li class="list-group-item active">Bài đăng của <?php echo $udata['name'];?></li>
<?php foreach ($articles as $article): ?>
<li class="list-group-item"><a href="<?php echo BASE_URL.'article/'.$article['id'].'/'.$article['slug'];?>"><?php echo $article['title'];?></a></li>
<?php endforeach?>
            </ul>
            <div class="pull-right">
                <a href="<?php echo BASE_URL.'author/'.$article['author_id'].'/'.$article['author'];?>">
                    &raquo; Xem thêm các bài viết của "<?php echo $article['author'];?>"
                </a>
            </div>
        <?php endif?>
        </div>
    </div>
</div>
<?php if (Session::get('uid') == $udata['id']): ?>
<script type="text/javascript">
    $(document).ready(function() {

        $('#email').change(function() {

            var email = $(this).val();

            $.post('<?php echo BASE_URL.'user/email_check_not_current'?>', { data: { data : email, uid : <?php echo Session::get('uid');?> } }, function(data) {
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

        $('#profile_submit').click(function(event) {
            event.preventDefault();

            var email = $('#email').val();
            var fullname = $('#fullname').val()
            $.post('<?php echo BASE_URL.'user/edit_profile_submit';?>', { data : { uid: <?php echo Session::get('uid');?>, data: email, fullname: fullname }}, function(data) {
                if (data.status == 'error') {
                    $('#email-msg').html(data.msg);
                } else{
                    $('#email-msg').html('');
                    $('#modal-message').html(data.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                };
            });
        });

        $('#user_bio').hover(function() {
            $('#edit_bio_button').show();
        }, function() {
            $('#edit_bio_button').hide();
        });

        $('#save_bio').click(function(event) {
            event.preventDefault();

            var bio = $('#bio').val();
            $.post('<?php echo BASE_URL.'user/edit_bio_submit';?>', { bio : bio }, function(data) {
                if (data.status == 'error') {
                    $('#bio-msg').html(data.msg);
                } else{
                    $('#bio-msg').html(data.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                };
            });
        });

        $('#show_password').click(function() {
            var $this = $(this);
            if ($this.is(':checked')) {
                $("#cur_pw").prop("type", "text");
                $("#new_pw").prop("type", "text");
                $("#new_pw_cf").prop("type", "text");
            } else {
                $("#cur_pw").prop("type", "password");
                $("#new_pw").prop("type", "password");
                $("#new_pw_cf").prop("type", "password");
            }
        });

        $('#cur_pw').focusout(function(event) {
            var pass = $(this).val();
            if (pass.length != 0) {
                $('#cur_pw_msg').html(' ');
                $.post('<?php echo BASE_URL.'user/check_current_password';?>', { current_password : pass }, function(data, textStatus, xhr) {
                    if (data.status == 'error') {
                        $('#cur_pw_msg').html(data.msg)
                        .closest('.form-group').removeClass('has-success').addClass('has-error');
                    } else{
                        $('#cur_pw_msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                        .closest('.form-group').removeClass('has-error').addClass('has-success');
                    };
                });
            } else{
                $('#cur_pw_msg').html('<span class="text-danger">Vui lòng nhập mật khẩu hiện tại!</span>');
            };
        });

        $('#change_password_button').click(function(event) {
            event.preventDefault();
            var crp = $('#cur_pw').val();
            var new_pw = $('#new_pw').val();
            var new_pw_cf = $('#new_pw_cf').val();
            if (crp.length == 0) {
                $('#cur_pw_msg').html('<span class="text-danger">Vui lòng nhập mật khẩu hiện tại!</span>')
                .closest('.form-group').removeClass('has-success').addClass('has-error');
                $('#cur_pw').focus();
            } else if (new_pw.length == 0) {
                $('#new_pw_msg').html('<span class="text-danger">Vui lòng nhập mật khẩu mới!</span>')
                .closest('.form-group').removeClass('has-success').addClass('has-error');
                $('#new_pw').focus();
            } else if (new_pw_cf.length == 0) {
                $('#new_pw_cf_msg').html('<span class="text-danger">Vui lòng nhập lại mật khẩu mới!</span>')
                .closest('.form-group').removeClass('has-success').addClass('has-error');
                $('#new_pw_cf').focus();
            } else {
                $('#cur_pw_msg').html(' ');
                $('#new_pw_msg').html(' ');
                $('#new_pw_cf_msg').html(' ');
                if (new_pw == new_pw_cf) {
                    $.post('<?php echo BASE_URL.'user/change_password';?>', { password : { current_password : crp, new_password : new_pw } }, function(data, textStatus, xhr) {
                        $('#change_password_msg').html(data.msg);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    });
                } else {
                    $('#new_pw_cf_msg').html('<span class="text-danger">Mật khẩu không trùng nhau!</span>');
                    $('#new_pw_cf').focus();
                };
            };
        });
    });
</script>
<?php endif;?>
<?php endif; // Main IF ?>