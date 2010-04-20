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
        <?php echo __('Num. code'); ?>
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
  <tbody class='property_values'>
    <?php foreach($form['SpecimensCodes'] as $form_value):?>
      <?php include_partial('spec_codes', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newCode'] as $form_value):?>
      <?php include_partial('spec_codes', array('form' => $form_value));?>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='8' class='add_value'>
        <a href="<?php echo url_for('specimen/addCode'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_prop_value"><?php echo __('Add Code');?></a>
      </td>
    </tr>
  </tfoot>
</table>