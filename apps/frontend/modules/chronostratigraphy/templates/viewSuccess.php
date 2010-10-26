<script type="text/javascript">
$(document).ready(function () 
{
   $('table.classifications_edit').find('.info').click(function() 
   {   
     if($('.tree').is(":hidden"))
     {
       $.get('<?php echo url_for('catalogue/tree?table=chronostratigraphy&id='.$chrono->Parent->getId()) ; ?>',function (html){
         $('.tree').html(html).slideDown();
         });
     }
     $('.tree').slideUp();
   });
});
</script>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue_chronostratigraphy','eid'=> $form->getObject()->getId(), 'view' => true)); ?>
<?php slot('title', __('View Chronostratigraphic unit'));  ?>
<div class="page">
    <h1><?php echo __('View Chronostratigraphic unit');?></h1>
  <div class="table_view">
  <table class="classifications_edit">
    <tbody>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $chrono->getName(); ?>
        </td>
        <td rowspan="6" class="keyword_row">
          <?php include_partial('catalogue/keywordsView', array('form' => $form,'table_name' => 'chronostratigraphy','field_name' => 'chronostratigraphy_name', 'view' => true)); ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $chrono->Level->getLevelName() ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['status']->renderLabel() ?></th>
        <td>
          <?php echo $chrono->getStatus() ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lower_bound']->renderLabel() ?></th>
        <td>
          <?php echo $chrono->getLowerBound() ; ?>
        </td>
      </tr> 
      <tr>
        <th><?php echo $form['upper_bound']->renderLabel() ?></th>
        <td>
          <?php echo $chrono->getUpperBound() ; ?>
        </td>
      </tr>       
      <tr>
        <th><?php echo $form['parent_ref']->renderLabel() ?></th>
        <td>
          <?php if ($chrono->Parent->getName() != "-") : ?>            
            <?php echo link_to(__($chrono->Parent->getName()), 'chronostratigraphy/view?id='.$chrono->Parent->getId(), array('id' => $chrono->Parent->getId())) ?>
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
	'category' => 'cataloguewidgetview',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'chronostratigraphy', 'view' => true)
	)); ?>
</div>
