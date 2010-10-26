<?php $read_only = (isset($view)&&$view)?true:false ; ?>
<table class="catalogue_table<?php echo($read_only?'_view':'') ;?>">
  <thead>
    <tr>
      <th><?php echo __('Language');?></th>
      <th><?php echo __('Is Mother Language');?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($langs as $lang):?>
  <tr>
    <td>
		  <?php if(!$read_only) : ?>    
      <a class="link_catalogue" title="<?php echo __('Edit Languages');?>"  href="<?php echo url_for('people/lang?ref_id='.$eid.'&id='.$lang->getId());?>">
	      <?php echo format_language($lang->getLanguageCountry());?>
      </a>
      <?php else : ?>
	      <?php echo format_language($lang->getLanguageCountry());?>  
      <?php endif ; ?>
      <?php if($lang->getPreferredLanguage()):?>
	(<?php echo __('Preferred');?>)
      <?php endif;?>
    </td>
    <td>
       <?php if($lang->getMother()):?>
	<?php echo __('Yes');?>
       <?php endif;?>
    </td>
    <td class="widget_row_delete">
		  <?php if(!$read_only) : ?>      
      <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=people_languages&id='.$lang->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </a>
      <?php endif ; ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>

<br />
<?php if(!$read_only) : ?>  
<?php echo image_tag('add_green.png');?>
<a title="<?php echo __('Add Language');?>" class="link_catalogue" href="<?php echo url_for('people/lang?ref_id='.$eid);?>"><?php echo __('Add');?></a>
<?php endif ; ?>
