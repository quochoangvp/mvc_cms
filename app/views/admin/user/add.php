<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="page-header"><?php if (isset($title)) echo $title;?></h2>
    <p id="message-ajax"></p>
    <form action="" method="POST" role="form" id="add_user_form">

        <div class="form-group">
            <label for="">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên người dùng" autofocus="" required="required">
            <p id="username-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Nhập địa chỉ email" autofocus="" required="required">
            <p id="email-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Họ tên</label>
            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập họ tên" autofocus="" required="required">
        </div>

        <div class="form-group">
            <label for="">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" autofocus="" required="required">
        </div>

        <div class="form-group">
            <label for="">Cấp độ</label>
            <select name="level" id="level" class="form-control" required="required">
                <option value="0"><?php echo Helper::level(0);?></option>
                <option value="1"><?php echo Helper::level(1);?></option>
                <option value="2"><?php echo Helper::level(2);?></option>
                <option value="3"><?php echo Helper::level(3);?></option>
            </select>
        </div>

        <button id="add_user" type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
<script type="text/javascript">
    $(document).ready(function() {

        $('#add_user_form').validate({
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
                level: {
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
                level: {
                    required: 'Trường này không được bỏ trống'
                },
            },
            highlight: function(element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                $('#add_user').attr('disabled','disabled');
            },

            errorElement: 'p',
            errorClass:'text-danger',

            success: function(element) {
                element
                .html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>').addClass('has-valid')
                .closest('.form-group').removeClass('has-error').addClass('has-success');
                $('#add_user').removeAttr('disabled');
            }
        });

        $('#username').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo BASE_URL.'user/username_check'?>', { data: data }, function(data) {
                    if (data.status == 'success') {
                        $('#username-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#add_user').removeAttr('disabled');
                    } else{
                        $('#username-msg').html(data.msg)
                        .closest('.form-group').removeClass('has-success').addClass('has-error');;
                        $('#add_user').attr('disabled','disabled');
                    };
                });
            } else {
                $('#add_user').attr('disabled','disabled');
            };
        });

        $('#email').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo BASE_URL.'user/email_check'?>', { data: data }, function(data) {
                    if (data.status == 'success') {
                        $('#email-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#add_user').removeAttr('disabled');
                    }
                    else
                    {
                        $('#email-msg').html(data.msg)
                            .closest('.form-group').removeClass('has-success').addClass('has-error');;
                            $('#add_user').attr('disabled','disabled');
                    };
                });
            }
            else
            {
                $('#add_user').attr('disabled','disabled');
            };
        });

        $('#add_user').click(function(event) {
            event.preventDefault();

            var username = $('#username').val();
            var email    = $('#email').val();
            var fullname = $('#fullname').val();
            var password = $('#password').val();
            var level = $('#level').val();

            var bt = $('#add_user');
            bt.button('loading');

            $.post('<?php echo ADM_URL.'user/add_submit';?>', {data : { username : username, fullname : fullname, email : email, password : password, level : level }}, function(data) {

                if (data.status == 'success') {

                    setTimeout(function () {
                        bt.button('reset');
                    }, 1500);

                    $('#message-ajax').slideDown().html(data.msg).delay(3000).slideUp();

                    $('#username').val('');
                    $('#email').val('');
                    $('#fullname').val('');
                    $('#password').val('');

                    $('.form-group').removeClass('has-success');
                    $('.form-group').find('span').remove();
                } else {
                    bt.button('reset');
                    $('#message-ajax').slideDown().html(data.msg).delay(3000).slideUp();
                };
            });
        })
    });
</script>