<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="page-header"><?php if (isset($title)) echo $title;?></h2>
    <p id="message-ajax"></p>
    <form action="" method="POST" role="form" id="add_category_form">

        <div class="form-group">
            <label for="">Trang trước</label>
            <select class="form-control" id="parent">
                <option value="0">Root</option>
                <?php foreach ($list_parent_categories as $parent): ?>
                    <option value="<?php echo $parent['id'];?>"><?php echo $parent['title'];?></option>
                <?php endforeach?>
            </select>
        </div>

        <div class="form-group">
            <label for="">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề" autofocus="" required="required">
            <p id="title-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Đường liên kết</label>
            <input type="text" class="form-control" id="slug" name="slug" placeholder="Nhập đường liên kết" autofocus="" required="required">
            <p id="slug-msg"></p>
        </div>

        <button id="add_category" type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
<script type="text/javascript">
    $(document).ready(function() {

        $('#title').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo ADM_URL.'category/title_check'?>', { data: data }, function(data) {
                    if (data.status == 'success') {
                        $('#title-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#add_category').removeAttr('disabled');
                    } else{
                        $('#title-msg').html(data.msg)
                        .closest('.form-group').removeClass('has-success').addClass('has-error');;
                        $('#add_category').attr('disabled','disabled');
                    };
                });
            } else {
                $('#add_category').attr('disabled','disabled');
            };
        });

        $('#slug').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo ADM_URL.'category/slug_check'?>', { data: data }, function(data) {
                    if (data.status == 'success') {
                        $('#slug-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#add_category').removeAttr('disabled');
                    }
                    else
                    {
                        $('#slug-msg').html(data.msg)
                            .closest('.form-group').removeClass('has-success').addClass('has-error');;
                            $('#add_category').attr('disabled','disabled');
                    };
                });
            }
            else
            {
                $('#add_category').attr('disabled','disabled');
            };
        });

        $('#add_category').click(function(event) {
            event.preventDefault();

            var title = $('#title').val();
            var slug  = $('#slug').val();
            var parent = $('#parent').val();

            var bt = $('#add_category');
            bt.button('loading');

            $.post('<?php echo ADM_URL.'category/add_submit';?>', {data : { title : title, slug : slug, parent : parent }}, function(data) {

                if (data.status == 'success') {

                    setTimeout(function () {
                        bt.button('reset');
                        window.location = "<?php echo ADM_URL.'category/edit/';?>"+data.id;
                    }, 1500);

                    $('#message-ajax').slideDown().html(data.msg).delay(3000).slideUp();

                    $('#title').val('');
                    $('#slug').val('');

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