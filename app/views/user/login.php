<div class="wrapper">
    <form class="form-signin login" method="POST">
        <h2 class="form-signin-heading">Đăng Nhập</h2>
        <p class="text-center" id="message"></p>
        <input type="text" class="form-control" id="email" name="email" placeholder="Địa chỉ email" required="" autofocus="" />
        <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required=""/>
        <label class="checkbox">
            <input type="checkbox" id="rememberMe" checked="" name="rememberMe"> Đăng nhập tự động
        </label>
        <button class="btn btn-lg btn-primary btn-block" id="login" type="submit">Đăng nhập</button>
        <label class="text">Bạn chưa có tài khoản? Hãy <a href="<?php echo BASE_URL.'user/register';?>">Đăng ký</a></label>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#login').click(function(event) {
            event.preventDefault();

            var btn = $(this);
            btn.button('loading');

            var email = $('#email').val();
            var password = $('#password').val();
            var rememberMe = $('#rememberMe').is(':checked') ? "TRUE" : "FALSE";

            $.post('<?php echo BASE_URL.'user/login_submit';?>', {user_data: { email: email, password : password, rememberMe : rememberMe }}, function(data) {

                if (data.status == 'success') {
                    $('#message').slideDown().html(data.msg).delay(3000);
                    setTimeout(function () {
                        window.location.replace("<?php echo BASE_URL.'"+data.url+"';?>");
                    }, 1500);
                } else {
                    $('#message').slideDown().html(data.msg).delay(3000).slideUp();
                };

                setTimeout(function () {
                    btn.button('reset');
                }, 1000);
            });

        });
    });
</script>