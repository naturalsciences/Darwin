<?php slot('Title', __('Edit User widgets'));  ?>        
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>                                                                                              
<div class="page">
  <h1 class="edit_mode">List of widgets available for <?php echo($user->getFamilyName()." ".$user->getGivenName()) ?></h1>
  <form class="edition" action="" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table>
  <thead class="title_widget">
  <tr>
  	<th>Category/screen</th><th>Name</th><th colspan="6">Widget</th>
  </tr>
  </thead>
  <?php foreach($form_pref as $category=>$record) :?>
  	<thead alt="<?php echo $category ?>" class='head_widget'>
  		<tr>
  			<td colspan="3" class='head_widget'>&nbsp;</td>
  			<?php if ($level > 2) : ?>
			<th class='head_widget'>Deactivated ?<br /><input type="radio" name="<?php echo('All_'.$category) ; ?>" value="unused"></th>
  			<?php endif ; ?>
  			<th class='head_widget'>Activated ?<br /><input type="radio" name="<?php echo('All_'.$category) ; ?>" value="is_available"></th>
  			<th class='head_widget'>Visible ?<br /><input type="radio" name="<?php echo('All_'.$category) ; ?>" value="visible"></th>
  			<th class='head_widget'>Opened ?<br /><input type="radio" name="<?php echo('All_'.$category) ; ?>" value="opened"></th>
  			<th class='head_widget'>Custom title</th>
  		</tr>	
	</thead>
	<tbody alt="<?php echo $category ?>" class='widget_selection'>
	<?php foreach($record as $i=>$widget) :?>
		<tr>
		    <?php if($i=='user_ref'):?>
			<?php echo("<th rowspan='".count($record)."' >".$category."</th>") ; ?>
		    <?php endif;?>
		    <th>
		      <?php echo $widget['widget_choice']->renderLabel() ?>
		      <?php echo $widget->renderHiddenFields(); ?>
		    </th>
		    <?php if ($form->getEmbeddedForm('MyPreferences')->getEmbeddedForm($widget->getName())->getObject()->getMandatory() ) : ?>
			    <th colspan="<?php echo ($level>2?5:4) ?>" class='widget_selection'>-----   Mandatory  -----</th>
		    <?php else : ?>
			    <td>
			      <?php echo $widget['widget_choice']->renderError() ?>
			      <?php echo $widget['widget_choice'] ?>
			    </td>
		    <?php endif ; ?>	
		    <td class='widget_selection'><?php echo $widget['title_perso']->renderError() ?>
			<?php echo $widget['title_perso'] ?>
		    </td>	
		</tr>
	<?php endforeach ?>
	</tbody>
  <?php endforeach ; ?>
<tfoot>
  <tr>
    <td colspan="5">
      <a href="<?php echo url_for('@homepage') ?>"><?php echo __('Cancel');?></a>
      <input id="reset" type="reset" value="Reset" />
      <input id="submit" type="submit" value="<?php echo __('Save');?>" />
    </td>
  </tr>
</tfoot>
 </table>
</form>
</div>
<script>
$('thead input[type=radio]').change(function()
{
  alt_val = $(this).closest('thead').attr('alt');
  $('tbody[alt="'+alt_val+'"] tr input[value="'+$(this).val()+'"]').attr("checked","checked");
});
</script>
