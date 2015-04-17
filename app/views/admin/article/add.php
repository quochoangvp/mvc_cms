<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="page-header"><?php if (isset($title)) echo $title;?></h2>
    <p id="message-ajax"></p>
    <form action="" method="POST" role="form" id="add_article_form">
        <div class="form-group">
            <label for="">Thể loại</label>
            <select class="form-control" id="category">
                <?php echo Helper::get_select_categories($list_categories, FALSE, $article_detail['cat_id']);?>
            </select>
        </div>

        <div class="form-group">
            <label for="">Ngày đăng</label>
            <input value="" type="text" class="form-control" id="pubdate" name="pubdate" placeholder="Ngày đăng bài" required="required">
            <p id="pubdate-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Tiêu đề</label>
            <input value="" type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề" autofocus="" required="required">
            <p id="title-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Đường liên kết</label>
            <input value="" type="text" class="form-control" id="slug" name="slug" placeholder="Nhập đường liên kết" autofocus="" required="required">
            <p id="slug-msg"></p>
        </div>

        <div class="form-group">
            <label for="">Nội dung</label>
            <textarea id="content" class="form-control" cols="20" rows="10"></textarea>
        </div>
        <button id="add_article" type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
<script type="text/javascript">
    $(document).ready(function() {

        $( "#pubdate" ).datepicker({ dateFormat: 'yy-mm-dd' });

        $('#title').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo ADM_URL.'article/title_check'?>', { data: data }, function(data) {
                    if (data.status == 'success') {
                        $('#title-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#add_article').removeAttr('disabled');
                    } else{
                        $('#title-msg').html(data.msg)
                        .closest('.form-group').removeClass('has-success').addClass('has-error');;
                        $('#add_article').attr('disabled','disabled');
                    };
                });
            } else {
                $('#add_article').attr('disabled','disabled');
            };
        });

        $('#slug').change(function() {
            var data = $(this).val();

            if (data.length >= 2) {
                $.post('<?php echo ADM_URL.'article/slug_check'?>', { data: data }, function(data) {
                    if (data.status == 'success') {
                        $('#slug-msg').html('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                            $('#add_article').removeAttr('disabled');
                    }
                    else
                    {
                        $('#slug-msg').html(data.msg)
                            .closest('.form-group').removeClass('has-success').addClass('has-error');;
                            $('#add_article').attr('disabled','disabled');
                    };
                });
            }
            else
            {
                $('#add_article').attr('disabled','disabled');
            };
        });

        $('#add_article').click(function(event) {
            event.preventDefault();

            var aid = $('#aid').val();
            var title = $('#title').val();
            var pubdate = $('#pubdate').val();
            var slug  = $('#slug').val();
            var cat_id = $('#category').val();
            var content = tinyMCE.activeEditor.getContent();

            var bt = $('#add_article');
            bt.button('loading');

            $.post('<?php echo ADM_URL.'article/add_submit';?>', {data : { id : aid, title : title, pubdate : pubdate, slug : slug, cid : cat_id, content : content }}, function(data) {

                if (data.status == 'success') {

                    setTimeout(function () {
                        bt.button('reset');
                        window.location = "<?php echo ADM_URL.'article/edit/';?>"+data.id;
                    }, 1500);

                    $('#message-ajax').slideDown().html(data.msg).delay(3000).slideUp();

                    $('#title').val('');
                    $('#slug').val('');
                    $('#pubdate').val('');
                    $('#content').val('');

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