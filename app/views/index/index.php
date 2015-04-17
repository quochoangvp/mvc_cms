<div class="page-header"><h2>Bài viết mới nhất</h2></div>
<div id="homepage_articles"></div>
<div id="load_status"></div>
<div id="button_load_more" class="text-center">
    <button id="load_more" type="button" value="2" class="btn btn-primary">Xem thêm</button>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#button_load_more').hide();
        $('#homepage_articles').html('<div class="loading_bar"></div>');
        $.post('<?php echo BASE_URL.'index/lastest_articles';?>', {  }, function(data) {
            $('#homepage_articles').html(data);
            $('#button_load_more').show();
        });

        $('#load_more').click(function(event) {
            event.preventDefault();
            var page = $(this).val();
            $('#button_load_more').hide();
            $('#load_status').addClass('loading_bar');
            $.post('<?php echo BASE_URL.'index/lastest_articles/';?>'+page, { }, function(data) {
                if (data.length > 1) {
                    $('#homepage_articles').append(data);
                    $('#button_load_more').show();
                    $('#load_more').val(parseInt(page)+1);
                } else{
                    $('#button_load_more').hide();
                };
                $('#load_status').removeClass('loading_bar');
            });
        });
    });
</script>