<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="page-header"><?php if (isset($title)) echo $title;?></h2>
    <p><span class="glyphicon glyphicon-plus"></span> <a href="<?php echo ADM_URL.'article/add';?>">Thêm bài viết mới</a></p>
    <hr>
    <p id="message-ajax"></p>
    <div id="show-list"></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $.post('<?php echo ADM_URL.'article/list_articles';?>', { }, function(data) {
            $('#show-list').html(data);
        });

    });
</script>