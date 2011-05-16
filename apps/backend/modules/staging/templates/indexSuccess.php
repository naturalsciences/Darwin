<div class="page">
  <form action="<?php echo url_for('staging/index');?>" method="post">
    <?php echo $form['slevel']->renderRow();?>
    <input type="submit"/>

    <hr />
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
    <?php include_partial('global/pager_info', array('form' => $form, 'pagerLayout' => $pagerLayout)); ?>  
  </form>
  <hr />

  <table>
    <thead>
    <?php foreach($fields as $name):?>
      <th><?php echo $name;?></th>
    <?php endforeach;?>
      <th></th>
    </thead>
    <?php foreach($search as $row):?>
      <tr>
        <?php foreach($fields as $name):?>
          <td style="border:1px solid gray"><?php echo $row[$name];?></td>
        <?php endforeach;?>
        <td><?php echo link_to(image_tag('edit.png', array("title" => __("Edit"))), 'staging/edit?id='.$row['id'].'&level='.$form['slevel']->getValue());?></td>
      </tr>
    <?php endforeach;?>
  </table>
  
</div>