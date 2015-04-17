<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2 class="page-header"><?php if (isset($title)) echo $title;?></h2>
    <p class="text-center" id="message-ajax">Hãy kéo thả các thể loại đến vị trí mong muốn và bấm "Lưu"</p>
    <div id="show-list"></div>
    <div class="pull-right"><button type="button" id="save" class="btn btn-primary">Lưu</button></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.post('<?php echo ADM_URL.'category/order_ajax'; ?>', { }, function(data, textStatus, xhr) {
            $('#show-list').html(data);
        });

        $('#save').click(function(event) {
            event.preventDefault();
            $(this).button('loading');
            data = $('.sortable').nestedSortable('toArray');
            $.post('<?php echo ADM_URL.'category/order_ajax'; ?>', { categories : data }, function(data, textStatus, xhr) {
                $('#save').button('reset');
                $('#show-list').html(data);
                $('#message-ajax').html('<span class="text-success">Lưu thành công!</span>');
                setTimeout(function() {
                    $('#message-ajax').html('');
                }, 3000);
            });
        });
    });
</script>