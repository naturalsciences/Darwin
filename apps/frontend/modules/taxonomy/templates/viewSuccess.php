<script type="text/javascript">
$(document).ready(function () 
{
   $('table.classifications_edit').find('.info').click(function() 
   {   
     if($('.tree').is(":hidden"))
     {
       $.get('<?php echo url_for('catalogue/tree?table=taxonomy&id='.$taxon->Parent->getId()) ; ?>',function (html){
         $('.tree').html(html).slideDown();
         });
     }
     $('.tree').slideUp();
   });
});
</script>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue_taxonomy','eid'=> $form->getObject()->getId())); ?>
<?php slot('title', __('View Taxonomic unit'));  ?>
<div class="page">
    <h1><?php echo __('View Taxonomic unit');?></h1>
  <div class="table_view">
  <table class="classifications_edit">
    <tbody>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $taxon->getName(); ?>
        </td>
        <td rowspan="6" class="keyword_row">
          <?php include_partial('catalogue/keywordsView', array('form' => $form,'table_name' => 'taxonomy','field_name' => 'taxonomy_name', 'view' => true)); ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $taxon->Level->getLevelName() ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['status']->renderLabel() ?></th>
        <td>
          <?php echo $taxon->getStatus() ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['extinct']->renderLabel() ?></th>
        <td>
          <?php echo $form['extinct'] ; ?>
        </td>
      </tr> 
      <tr>
        <th><?php echo $form['parent_ref']->renderLabel() ?></th>
        <td>
          <?php if ($taxon->Parent->getName() != "-") : ?>
          <?php echo link_to(__($taxon->Parent->getName()), 'taxonomy/view?id='.$taxon->Parent->getId(), array('id' => $taxon->Parent->getId())) ?>
          <?php echo image_tag('info.png',"title=info class=info");?>
          <div class="tree">
          </div>
          <?php else : ?>
          -
          <?php endif ; ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>  
 <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'cataloguewidget',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'taxonomy', 'view' => true)
	)); ?>
</div>
