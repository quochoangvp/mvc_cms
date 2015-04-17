
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo BASE_URL;?>">MVC CMS</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php if (Session::get('uid')): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo Session::get('uname');?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a class="blog-nav-item" href="<?php echo BASE_URL.'user/profile';?>">Hồ sơ</a></li>
                        <li><a class="blog-nav-item" href="<?php echo BASE_URL.'user/logout';?>">Thoát</a></li>
                    </ul>
                </li>
                <?php endif?>
            </ul>
        </div>
    </div>
</nav>
