<?php include_javascripts_for_form($form) ?>
<div id="catalogue_relation_screen">

<form class="edition qtiped_form" action="<?php echo url_for('catalogue/relation?table='.$sf_params->get('table').'&rid='.$sf_request->getParameter('rid'). '&type='.$sf_request->getParameter('type') . ($form->getObject()->isNew() ? '': '&id='.$form->getObject()->getId() ) );?>" method="post" id="relation_form">
<?php echo $form->renderHiddenFields();?>
<table>
  <tbody>
    <tr>
        <td colspan="2">
          <?php echo $form->renderGlobalErrors() ?>
        </td>
    </tr>
    <tr>
      <th><?php echo $form['record_id_2']->renderLabel( ($form->getObject()->getRelationshipType() == 'current_name')? 'Renamed in': 'Recombined From' );?></th>
      <td>
        <?php echo $form['record_id_2']->renderError(); ?>
        <?php echo $form['record_id_2'];?>
      </td>
    </tr>
    <tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
	  <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=catalogue_relationships&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
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
   $(document).ready(function () 
   {
     $('.result_choose').live('click', chooseResult("#catalogue_relationships_record_id_2"));
   });
</script>

<div class="search_box show">
  <?php include_partial('catalogue/chooseItem', array('searchForm' => $searchForm,'is_choose'=>true)) ?>
</div>

</div>