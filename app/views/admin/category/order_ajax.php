<?php
if (isset($categories)) {
    echo Helper::get_order_ajax($categories);
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            maxLevels: 2
        });
    });
</script>