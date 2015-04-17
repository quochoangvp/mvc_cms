<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : 'CMS';?></title>
    <!-- Latest compiled and minified CSS & JS -->
    <link rel="stylesheet" media="screen" href="<?php echo CSS_URL;?>bootstrap.min.css">
    <link rel="stylesheet" media="screen" href="<?php echo CSS_URL;?>blog.css">
    <link rel="stylesheet" media="screen" href="<?php echo CSS_URL;?>comment.css">
    <?php if (isset($login)): ?>
    <link rel="stylesheet" media="screen" href="<?php echo CSS_URL;?>login.css">
    <?php endif?>
    <script src="<?php echo JS_URL;?>jquery.min.js"></script>
    <script src="<?php echo JS_URL;?>bootstrap.min.js"></script>
    <script src="<?php echo JS_URL;?>jquery.validate.min.js"></script>
</head>
<body>

<?php if (isset($navi) && $navi == TRUE) include_once 'nav.php'; ?>

<div class="container">
    <?php if (isset($blog_des) && $blog_des == TRUE): ?>
    <div class="blog-header">
        <h1 class="blog-title">The Bootstrap Blog</h1>
        <p class="lead blog-description">The official example template of creating a blog with Bootstrap.</p>
    </div>
    <?php else: ?>
    <div style="padding: 10px 0px;">
    </div>
<?php endif?>
