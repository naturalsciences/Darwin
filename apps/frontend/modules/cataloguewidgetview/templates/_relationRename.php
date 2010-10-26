<table class="catalogue_table_view">
  <thead>
    <tr>
      <th><?php echo __('Renamed to');?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($relations as $renamed):?>
  <tr>
    <td>
      <?php echo $renamed['ref_item']->getNameWithFormat()?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
