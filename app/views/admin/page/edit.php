<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="page-header"><?php if (isset($title)) echo $title;?></h2>
    <p id="message-ajax"></p>
    <form action="" method="POST" role="form" id="edit_page_form">

        <div class="form-group">
            <label for="">Trang trước</label>
            <select class="form-control" id="parent">
                <option value="0">Root</option>
                <?php foreach ($list_parent_pages as $parent): ?>
                    <option value="<?php echo $parent['id'];?>" <?php
                        echo ($parent['id'] == $page_detail['parent_id']) ? 'selected="selected"' : '';
                        ?>><?php echo $parent['title'];?>
                    </option>
                <?php endforeach?>
            </select>
        </div>

        <div class="form-group">
            <label for="">Tiêu đề</label>
            <input value="<?php echo $page_detail['title'];?>" type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề" autofocus="" required="required">
            <p id="title-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Đường liên kết</label>
            <input value="<?php echo $page_detail['slug'];?>" type="text" class="form-control" id="slug" name="slug" placeholder="Nhập đường liên kết" autofocus="" required="required">
            <p id="slug-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Nội dung</label>
            <textarea id="content" class="form-control" cols="20" rows="10"><?php echo html_entity_decode($page_detail['content']);?></textarea>
        </div>
        <input value="<?php echo $page_detail['id'];?>" type="hidden" id="pid" name="pid">
        <button id="edit_page" type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        var pid = $('#pid').val();

        $('#title').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo ADM_URL.'page/title_check_not_current'?>', { data: { id : pid, data : data} }, function(data) {
                    if (data.status == 'success') {
                        $('#title-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#edit_page').removeAttr('disabled');
                    } else{
                        $('#title-msg').html(data.msg)
                        .closest('.form-group').removeClass('has-success').addClass('has-error');;
                        $('#edit_page').attr('disabled','disabled');
                    };
                });
            } else {
                $('#edit_page').attr('disabled','disabled');
            };
        });

        $('#slug').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo ADM_URL.'page/slug_check_not_current'?>', { data: { id : pid, data : data} }, function(data) {
                    if (data.status == 'success') {
                        $('#slug-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#edit_page').removeAttr('disabled');
                    }
                    else
                    {
                        $('#slug-msg').html(data.msg)
                        .closest('.form-group').removeClass('has-success').addClass('has-error');;
                        $('#edit_page').attr('disabled','disabled');
                    };
                });
            } else {
                $('#edit_page').attr('disabled','disabled');
            };
        });

        $('#edit_page').click(function(event) {
            event.preventDefault();

            var title = $('#title').val();
            var slug  = $('#slug').val();
            var parent = $('#parent').val();
            var template = $('#template').val();
            var content = tinyMCE.activeEditor.getContent();

            var bt = $('#edit_page');
            bt.button('loading');

            $.post('<?php echo ADM_URL.'page/edit_submit';?>', {data : { id : pid, title : title, slug : slug, parent : parent, template : template, content : content }}, function(data) {

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