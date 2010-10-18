<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue_igs','eid'=> $form->getObject()->getId())); ?>
<?php slot('title', __('View Igs'));  ?>
<div class="page">
    <h1><?php echo __('View Igs');?></h1>
  <div class="table_view">
  <table>
    <tbody>
      <tr>
        <th><?php echo $form['ig_num']->renderLabel() ?></th>
        <td>
          <?php echo $igs->getIgNum(); ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['ig_date']->renderLabel() ?></th>
        <td>
          <?php echo FuzzyDateTime::getDateTimeStringFromArray($igs->getIgDate()->getRawValue()) ?>
        </td>
      </tr>     
    </tbody>
  </table>
</div>  
 <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'cataloguewidget',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'igs', 'level' => $level)
	)); ?>
</div>
