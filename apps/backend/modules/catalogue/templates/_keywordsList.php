<ul class="name_tags">
  <?php foreach(ClassificationKeywords::getTags() as $key => $name):?>
    <li alt="<?php echo $key;?>"><?php echo __($name);?></li>
  <?php endforeach;?>
</ul>