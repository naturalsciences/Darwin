<?php slot('title', __( $form->isNew() ? 'Add Part' : 'Edit part'));  ?>
<?php use_stylesheet('widgets.css') ?>
<?php use_javascript('widgets.js') ?>
<?php use_javascript('catalogue.js') ?>
<?php use_javascript('button_ref.js') ?>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'part','eid'=> $part->getId(), 'table' => 'specimen_parts')); ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'individual'=> $individual, 'part'=> $part ,'mode' => 'parts_edit') );?>

  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>

  <div>
    <ul id="error_list" class="error_list" style="display:none">
      <li></li>
    </ul>
  </div>

  <input id="collection_id" type="hidden" value="<?php echo $specimen->getCollectionRef();?>">
  <?php echo form_tag('parts/edit'. ($form->isNew() ? '?indid='.$individual->getId() : '?id='.$form->getObject()->getId()) );?>
    <div>
      <?php if($form->hasGlobalErrors()):?>
        <ul class="spec_error_list">
          <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
            <?php if(!in_array($error,$form->getErrorSchema()->getErrors())) : ?>					  
              <li><?php echo __($name." ".$error); ?></li>
            <?php endif ; ?>
          <?php endforeach; ?>
          <?php foreach ($form->getErrorSchema()->getErrors() as $name => $error): ?>
            <li class="error_fld_<?php echo $name;?>"><?php echo __($name." ".$error) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif;?>

      <?php include_partial('widgets/screen', array(
        'widgets' => $widgets,
        'category' => 'partwidget',
        'columns' => 2,
        'options' => array('form' => $form)
      )); ?>
      <p class="clear"></p>
      <p class="form_buttons">
          <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to(__('New part'), 'parts/edit?indid='.$individual->getId()) ?>
            &nbsp;<?php echo link_to(__('Duplicate part'), 'parts/edit?indid='.$individual->getId().'&duplicate_id='.$part->getId()) ?>
            &nbsp;<a href="<?php echo url_for('catalogue/deleteRelated?table=specimen_parts&id='.$part->getId());?>" title="<?php echo __('Are you sure ?') ?>" id="spec_part_delete"><?php echo __('Delete');?></a>
          <?php endif?>

          &nbsp;<a href="<?php echo url_for('parts/overview?id='.$individual->getId()) ?>"><?php echo __('Cancel');?></a>
        <input type="submit" value="<?php echo __('Save');?>" id="submit_spec_f1"/>
      </p>
<script  type="text/javascript">

function addError(html)
{
  $('ul#error_list').find('li').text(html);
  $('ul#error_list').show();
}

function removeError()
{
  $('ul#error_list').hide();
  $('ul#error_list').find('li').text(' ');
}

$(document).ready(function () {
    $('body').catalogue({});
  $("a#spec_part_delete").click(function(){
     if(confirm($(this).attr('title')))
     {
       currentElement = $(this);
       removeError();
       $.ajax({
               url: $(this).attr('href'),
               success: function(html) {
		      if(html == "ok" )
		      {
			// Reload page
			$(location).attr('href',$('a#tab_3').attr('href'));
		      }
		      else
		      {
			addError(html); //@TODO:change this!
		      }
		},
               error: function(xhr){
		  addError('Error!  Status = ' + xhr.status);
               }
             }
            );
    }
    return false;
  });
});
</script>

<?php include_partial('specimen/specAfterTab');?>

