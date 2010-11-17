<?php foreach($Comments as $comment):?>      
  <fieldset class="opened"><legend><b><?php echo __('Notion');?></b> : <?php echo $comment->getNotionConcerned();?></legend>
    <?php echo $comment->getComment() ;?>
  </fieldset>
<?php endforeach ; ?>    
