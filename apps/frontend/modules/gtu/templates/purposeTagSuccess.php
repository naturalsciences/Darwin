<ul>
  <?php foreach($tags as $tag):?>
    <li class="tag_size_<?php echo $tag['size'];?>"><?php echo $tag['tag'];?></li>
  <?php endforeach;?>
</ul>