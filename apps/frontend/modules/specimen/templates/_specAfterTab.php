       </div>
	</div>
<?php if(!isset($specimen) || $specimen->isNew()):?>
  <?php echo image_tag('encod_right_disable.png','id="arrow_right" alt="'.__('Go next').'" class="scrollButtons right"');?>
<?php else:?>
  <?php echo link_to(image_tag('encod_right_enable.png','id="arrow_right" alt="'.__('Go next').'" class="scrollButtons right"'),'individuals/edit?id='.$specimen->getId());?>
<?php endif;?>
  </div>