
<div class="sidebar-module">
    <h4>Các thể loại</h4>
    <?php if (isset($sidebar_categories)) {
        echo Helper::sidebar_categories($sidebar_categories);
    }?>
</div>
<div class="sidebar-module">
    <h4>Elsewhere</h4>
    <ol class="list-unstyled">
      <li><a href="#">GitHub</a></li>
      <li><a href="#">Twitter</a></li>
      <li><a href="#">Facebook</a></li>
  </ol>
</div>