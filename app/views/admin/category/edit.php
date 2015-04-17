<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="page-header"><?php if (isset($title)) echo $title;?></h2>
    <p id="message-ajax"></p>
    <form action="" method="POST" role="form" id="edit_category_form">

        <div class="form-group">
            <label for="">Trang trước</label>
            <select class="form-control" id="parent">
                <option value="0">Root</option>
                <?php foreach ($list_parent_categories as $parent): ?>
                    <option value="<?php echo $parent['id'];?>" <?php
                        echo ($parent['id'] == $category_detail['parent_id']) ? 'selected="selected"' : '';
                    ?>><?php echo $parent['title'];?></option>
                <?php endforeach?>
            </select>
        </div>

        <div class="form-group">
            <label for="">Tiêu đề</label>
            <input value="<?php echo $category_detail['title'];?>" type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề" autofocus="" required="required">
            <p id="title-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Đường liên kết</label>
            <input value="<?php echo $category_detail['slug'];?>" type="text" class="form-control" id="slug" name="slug" placeholder="Nhập đường liên kết" autofocus="" required="required">
            <p id="slug-msg"></p>
        </div>

        <input value="<?php echo $category_detail['id'];?>" type="hidden" id="cid" name="cid">
        <button id="edit_category" type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        var cid = $('#cid').val();

        $('#title').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo ADM_URL.'category/title_check_not_current'?>', { data: { id : cid, data : data} }, function(data) {
                    if (data.status == 'success') {
                        $('#title-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#edit_category').removeAttr('disabled');
                    } else{
                        $('#title-msg').html(data.msg)
                        .closest('.form-group').removeClass('has-success').addClass('has-error');;
                        $('#edit_category').attr('disabled','disabled');
                    };
                });
            } else {
                $('#edit_category').attr('disabled','disabled');
            };
        });

        $('#slug').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo ADM_URL.'category/slug_check_not_current'?>', { data: { id : cid, data : data} }, function(data) {
                    if (data.status == 'success') {
                        $('#slug-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#edit_category').removeAttr('disabled');
                    }
                    else
                    {
                        $('#slug-msg').html(data.msg)
                        .closest('.form-group').removeClass('has-success').addClass('has-error');;
                        $('#edit_category').attr('disabled','disabled');
                    };
                });
            } else {
                $('#edit_category').attr('disabled','disabled');
            };
        });

        $('#edit_category').click(function(event) {
            event.preventDefault();

            var title = $('#title').val();
            var slug  = $('#slug').val();
            var parent = $('#parent').val();

            var bt = $('#edit_category');
            bt.button('loading');

            $.post('<?php echo ADM_URL.'category/edit_submit';?>', {data : { id : cid, title : title, slug : slug, parent : parent }}, function(data) {

                if (data.status == 'success') {

                    setTimeout(function () {
                        bt.button('reset');
                    }, 1500);

                    $('#message-ajax').slideDown().html(data.msg).delay(3000).slideUp();

                    $('.form-group').removeClass('has-success');
                    $('.form-group').find('span.glyphicon').remove();

                } else {
                    bt.button('reset');
                    $('#message-ajax').slideDown().html(data.msg).delay(3000).slideUp();
                };
            });
        })
    });
</script>