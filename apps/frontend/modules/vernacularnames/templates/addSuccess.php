<?php include_javascripts_for_form($form) ?>
<div id="vernacular_screen">
<form class="edition qtiped_form" action="<?php echo url_for('vernacularnames/add?table='.$sf_request->getParameter('table').'&id='.$sf_request->getParameter('id') . ($form->getObject()->isNew() ? '': '&rid='.$form->getObject()->getId() ) );?>" method="post" id="property_form">
<?php echo $form['referenced_relation'];?>
<?php echo $form['record_id'];?>
<table>
  <tbody>
    <tr>
      <td colspan="2">
        <?php echo $form->renderGlobalErrors() ?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['community']->renderLabel();?></th>
      <td>
        <?php echo $form['community']->renderError(); ?>
        <?php echo $form['community'];?>
      </td>
    </tr>
  </tbody>
</table>
<table class="encoding property_values">
  <thead>
    <tr>
      <th colspan="2"><label><?php echo __('Vernacular name');?></label></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($form['VernacularNames'] as $form_value):?>
      <?php include_partial('vernacular_names_values', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newVal'] as $form_value):?>
      <?php include_partial('vernacular_names_values', array('form' => $form_value));?>
    <?php endforeach;?>
  </tbody>
</table>
<div class='add_value'>
  <a href="<?php echo url_for('vernacularnames/addValue'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_prop_value">Add Value</a>
</div>
<table class="bottom_actions">
  <tfoot>
    <tr>
      <td>
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
          <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=class_vernacular_names&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
            <?php echo __('Delete');?>
          </a>
        <?php endif;?>
        <input id="submit" type="submit" value="<?php echo __('Save');?>" />
      </td>
    </tr>
  </tfoot>
</table>
</form>

<script  type="text/javascript">
  $(document).ready(function () {
    $('.clear_prop').live('click', clearPropertyValue);

    $('#add_prop_value').click(addPropertyValue);
  });
</script>
</div>
