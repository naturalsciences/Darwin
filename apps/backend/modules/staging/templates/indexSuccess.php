<div class="page">
  <form action="" method="post">
    <?php echo $form['slevel']->renderRow();?>
    <input type="submit"/>
  </form>
  <hr />
  <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
  <?php include_partial('global/pager_info', array('form' => $form, 'pagerLayout' => $pagerLayout)); ?>  

  <hr />

  <table>
    <thead>
    <?php foreach($fields as $name):?>
      <th><?php echo $name;?></th>
    <?php endforeach;?>
    </thead>
    <?php foreach($search as $row):?>
      <tr>
        <?php foreach($fields as $name):?>
          <td style="border:1px solid gray"><?php echo $row[$name];?></td>
        <?php endforeach;?>
      </tr>
    <?php endforeach;?>
  </table>
  
</div>