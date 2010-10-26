<script type="text/javascript">
$(document).ready(function () 
{
   $('table.classifications_edit').find('.info').click(function() 
   {   
     if($('.tree').is(":hidden"))
     {
       $.get('<?php echo url_for('catalogue/tree?table=mineralogy&id='.$mineral->Parent->getId()) ; ?>',function (html){
         $('.tree').html(html).slideDown();
         });
     }
     $('.tree').slideUp();
   });
});
</script>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue_mineralogy','eid'=> $form->getObject()->getId(), 'view' => true)); ?>
<?php slot('title', __('View Mineralogic unit'));  ?>
<div class="page">
    <h1><?php echo __('View Mineralogic unit');?></h1>
  <div class="table_view">
  <table class="classifications_edit">
    <tbody>
      <tr>
        <th><?php echo $form['code']->renderLabel() ?></th>
        <td>
          <?php echo $mineral->getCode() ?>
        </td>
      </tr>    
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $mineral->getName(); ?>
        </td>
        <td rowspan="6" class="keyword_row">
          <?php include_partial('catalogue/keywordsView', array('form' => $form,'table_name' => 'mineralogy','field_name' => 'mineralogy_name', 'view' => true)); ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['classification']->renderLabel() ?></th>
        <td>
          <?php echo $mineral->getClassification() ?>
        </td>
      </tr>      
      <tr>
        <th><?php echo $form['level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $mineral->Level->getLevelName() ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['status']->renderLabel() ?></th>
        <td>
          <?php echo $mineral->getStatus() ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['parent_ref']->renderLabel() ?></th>
        <td>
          <?php if ($mineral->Parent->getName() != "-") : ?>        
            <?php echo link_to(__($mineral->Parent->getName()), 'mineralogy/view?id='.$mineral->Parent->getId(), array('id' => $mineral->Parent->getId())) ?>
            <?php echo image_tag('info.png',"title=info class=info");?>
            <div class="tree">
            </div>
          <?php else : ?>
          -
          <?php endif ; ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['cristal_system']->renderLabel() ?></th>
        <td>
          <?php echo $mineral->getCristalSystem() ?>
        </td>
      </tr>      
    </tbody>
  </table>
</div>  
 <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'cataloguewidgetview',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'mineralogy', 'view' => true)
	)); ?>
</div>
