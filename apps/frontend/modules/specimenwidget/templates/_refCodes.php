<table>
  <thead>
    <tr>
      <th>
        <?php echo __('Category'); ?>
      </th>
      <th>
        <?php echo __('Prefix'); ?>
      </th>
      <th>
        <?php echo __('sep.'); ?>
      </th>
      <th>
        <?php echo __('Numeric code'); ?>
      </th>
      <th>
        <?php echo __('sep.'); ?>
      </th>
      <th>
        <?php echo __('Suffix'); ?>
      </th>
      <th colspan='2'>
        <?php echo __('Date'); ?>
      </th>
    </tr>
    <tr>
      <th colspan='2'>
      </th>
      <th>
      </th>
      <th>
      </th>
      <th>
      </th>
      <th colspan='3'>
      </th>
    </tr>
  </thead>
  <tbody>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='8' class='add_value'>
        <a href="<?php echo url_for('codes/addCode'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_prop_value"><?php echo __('Add Code');?></a>
      </td>
    </tr>
  </tfoot>
</table>