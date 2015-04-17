<div class="wrapper">
    <form class="form-signin register" id="registerForm" method="POST">
        <h2 class="form-signin-heading">Đăng Ký</h2>
        <p id="message-ajax"></p>
        <div class="control-group">
            <label class="reg-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Tên người dùng" required="" autofocus="" />
            <p id="username-msg"></p>
        </div>
        <div class="control-group">
            <label class="reg-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Địa chỉ email" required="" autofocus="" />
            <p id="email-msg"></p>
        </div>
        <div class="control-group">
            <label class="reg-label">Họ tên</label>
            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Họ và tên" required="" autofocus="" />
        </div>
        <div class="control-group">
            <label class="reg-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required=""/>
        </div>
        <div class="control-group">
            <label class="reg-label">Nhập lại mật khẩu</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Nhập lại mật khẩu" required=""/>
        </div>
        <div class="control-group">
            <button class="btn btn-lg btn-primary btn-block" id="reg_submit" type="submit">Đăng ký</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('#registerForm').validate({
            ignore: "#username, #email",
            rules: {
                fullname: {
                    minlength: 2,
                    required: true
                },
                password: {
                    minlength: 4,
                    required: true
                },
                password_confirm: {
                    equalTo: "#password",
                    required: true
                }
            },
            messages: {
                fullname: {
                    minlength: 'Họ tên ít nhất là 2 ký tự',
                    required: 'Trường này không được bỏ trống'
                },
                password: {
                    minlength: 'Mật khẩu ít nhất là 4 ký tự',
                    required: 'Trường này không được bỏ trống'
                },
                password_confirm: {
                    required: 'Trường này không được bỏ trống',
                    equalTo: 'Mật khẩu không khớp nhau'
                },
            },
            highlight: function(element) {
                $(element).closest('.control-group').removeClass('has-success').addClass('has-error');
                $('#reg_submit').attr('disabled','disabled');
            },

            errorElement: 'p',
            errorClass:'text-danger',

            success: function(element) {
                element
                .html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>').addClass('has-valid')
                .closest('.control-group').removeClass('has-error').addClass('has-success');
                $('#reg_submit').removeAttr('disabled');
            }
        });

        $('#username').change(function() {
            var data = $(this).val();

            if (data.length != 0) {
                $.post('<?php echo BASE_URL.'user/username_check'?>', { data: data }, function(data) {
                    if (data.status == 'success') {
                        $('#username-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.control-group').removeClass('has-error').addClass('has-success');
                            $('#reg_submit').removeAttr('disabled');
                    } else{
                        $('#username-msg').html(data.msg)
                        .closest('.control-group').removeClass('has-success').addClass('has-error');;
                        $('#reg_submit').attr('disabled','disabled');
                    };
                });
            };
        });

        $('#email').change(function() {
            var data = $(this).val();

            if (data.length != 0) {
                $.post('<?php echo BASE_URL.'user/email_check'?>', { data: data }, function(data) {
                    if (data.status == 'success') {
                        $('#email-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.control-group').removeClass('has-error').addClass('has-success');
                            $('#reg_submit').removeAttr('disabled');
                    } else{
                        $('#email-msg').html(data.msg)
                        .closest('.control-group').removeClass('has-success').addClass('has-error');;
                        $('#reg_submit').attr('disabled','disabled');
                    };
                });
            };
        });

        $('#reg_submit').click(function(event) {
            event.preventDefault();

            var username = $('#username').val();
            var email = $('#email').val();
            var fullname = $('#fullname').val();
            var password = $('#password').val();

            var bt = $('#reg_submit');
            bt.button('loading');

            $.post('<?php echo BASE_URL.'user/register_submit';?>', {data : { username : username, fullname : fullname, email : email, password : password }}, function(data) {
                setTimeout(function () {
                    bt.button('reset');
                }, 1500);

                if (data.status == 'success') {
                    $('#message-ajax').slideDown().html(data.msg).delay(3000);
                    setTimeout(function () {
                        window.location.replace("<?php echo BASE_URL.'user/login';?>");
                    }, 1500);
                } else {
                    $('#message-ajax').slideDown().html(data.msg).delay(3000).slideUp();
                };
            });
        })
    });
</script>