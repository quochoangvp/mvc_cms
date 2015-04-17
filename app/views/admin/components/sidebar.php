
<div class="col-sm-3 col-md-2 sidebar" id="MainSidebar">
    <div class="list-group nav-sidebar">
        <a class="list-group-item list-group-item-success <?php echo $GLOBALS['rz'][0] == 'dashboard' ? 'active' : '';?>" href="<?php echo ADM_URL.'dashboard';?>">Bảng điều khiển</a>

        <a href="#usermanage" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainSidebar">
            Người dùng <span class="pull-right glyphicon glyphicon-menu-<?php echo $GLOBALS['rz'][0] == 'user' ? 'down' : 'right';?>"></span>
        </a>
        <div class="collapse <?php echo $GLOBALS['rz'][0] == 'user' ? 'in' : '';?>" id="usermanage">
            <a href="<?php echo ADM_URL.'user/add';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'user' && $GLOBALS['rz'][1] == 'add' ? 'active' : '';?>">Thêm người dùng</a>
            <a href="<?php echo ADM_URL.'user/index';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'user' && ($GLOBALS['rz'][1] == 'index' || $GLOBALS['rz'][1] == '') ? 'active' : '';?>">Danh sách</a>
        </div>

        <a href="#pagemanage" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainSidebar">
            Quản lý trang <span class="pull-right glyphicon glyphicon-menu-<?php echo $GLOBALS['rz'][0] == 'page' ? 'down' : 'right';?>"></span>
        </a>
        <div class="collapse <?php echo $GLOBALS['rz'][0] == 'page' ? 'in' : '';?>" id="pagemanage">
            <a href="<?php echo ADM_URL.'page/add';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'page' && $GLOBALS['rz'][1] == 'add' ? 'active' : '';?>">Thêm trang</a>
            <a href="<?php echo ADM_URL.'page/index';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'page' && ($GLOBALS['rz'][1] == 'index' || $GLOBALS['rz'][1] == '') ? 'active' : '';?>">Danh sách</a>
            <a href="<?php echo ADM_URL.'page/order';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'page' && ($GLOBALS['rz'][1] == 'order') ? 'active' : '';?>">Sắp xếp</a>
        </div>

        <a href="#categories" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainSidebar">
            Quản lý thể loại <span class="pull-right glyphicon glyphicon-menu-<?php echo $GLOBALS['rz'][0] == 'category' ? 'down' : 'right';?>"></span>
        </a>
        <div class="collapse <?php echo $GLOBALS['rz'][0] == 'category' ? 'in' : '';?>" id="categories">
            <a href="<?php echo ADM_URL.'category/add';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'category' && $GLOBALS['rz'][1] == 'add' ? 'active' : '';?>">Thêm thể loại</a>
            <a href="<?php echo ADM_URL.'category/index';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'category' && ($GLOBALS['rz'][1] == 'index' || $GLOBALS['rz'][1] == '') ? 'active' : '';?>">Danh sách</a>
            <a href="<?php echo ADM_URL.'category/order';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'category' && ($GLOBALS['rz'][1] == 'order') ? 'active' : '';?>">Sắp xếp</a>
        </div>

        <a href="#articles" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainSidebar">
            Quản lý bài viết <span class="pull-right glyphicon glyphicon-menu-<?php echo $GLOBALS['rz'][0] == 'article' ? 'down' : 'right';?>"></span>
        </a>
        <div class="collapse <?php echo $GLOBALS['rz'][0] == 'article' ? 'in' : '';?>" id="articles">
            <a href="<?php echo ADM_URL.'article/add';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'article' && $GLOBALS['rz'][1] == 'add' ? 'active' : '';?>">Thêm bài viết</a>
            <a href="<?php echo ADM_URL.'article/index';?>" class="list-group-item <?php echo $GLOBALS['rz'][0] == 'article' && ($GLOBALS['rz'][1] == 'index' || $GLOBALS['rz'][1] == '') ? 'active' : '';?>">Danh sách</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('[data-toggle=collapse]').click(function(){
        $(this).find("span").toggleClass("glyphicon-menu-right glyphicon-menu-down");
    });
</script>