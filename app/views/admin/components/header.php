<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : 'CMS';?></title>
    <!-- Latest compiled and minified CSS & JS -->
    <link rel="stylesheet" media="screen" href="<?php echo CSS_URL;?>bootstrap.min.css">
    <link rel="stylesheet" media="screen" href="<?php echo CSS_URL;?>admin/dashboard.css">
    <link rel="stylesheet" media="screen" href="<?php echo CSS_URL;?>jquery-ui.min.css">
    <?php if (isset($sortable)): ?><link rel="stylesheet" media="screen" href="<?php echo CSS_URL;?>jquery.dataTables.css"><?php endif?>

    <script src="<?php echo JS_URL;?>jquery.min.js"></script>
    <script src="<?php echo JS_URL;?>jquery-ui.min.js"></script>
    <script src="<?php echo JS_URL;?>bootstrap.min.js"></script>
    <script src="<?php echo JS_URL;?>jquery.validate.min.js"></script>
    <?php if (isset($sortable)): ?><script src="<?php echo JS_URL;?>jquery.dataTables.min.js"></script><?php endif?>
    <?php if (isset($nested)): ?><script src="<?php echo JS_URL;?>jquery.mjs.nestedSortable.js"></script><?php endif?>
    <?php if (isset($tinymce)): ?><script src="<?php echo JS_URL;?>tinymce/tinymce.min.js"></script>
    <script>
    tinymce.init({
        selector: "textarea",
        theme: "modern",
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality emoticons template paste textcolor"
       ],
       content_css: "css/content.css",
       toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
       style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
     });
    </script><?php endif?>

</head>
<body>

<?php
if (isset($navi) && $navi == TRUE)
{
    include_once 'nav.php';
}
?>

    <div class="container-fluid">
        <div class="row">
<?php
if (isset($sidebar) && $sidebar == TRUE)
{
    include_once 'sidebar.php';
}