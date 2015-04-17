<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="page-header"><?php if (isset($title)) echo $title;?></h2>
    <p><span class="glyphicon glyphicon-plus"></span> <a href="<?php echo ADM_URL.'user/add';?>">Thêm người dùng</a></p>
    <hr>
    <p id="message-ajax"></p>
    <div id="show-list"></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $.post('<?php echo ADM_URL.'user/list_users';?>', { }, function(data) {
            $('#show-list').html(data);
        });

    });
</script>