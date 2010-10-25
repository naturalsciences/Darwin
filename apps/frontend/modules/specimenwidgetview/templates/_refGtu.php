<table class="catalogue_table_view">
  <tbody>
    <tr>
      <th>
        <?php echo __('Station visible ?') ?>
      </th>
      <td>
        <?php echo $spec->getStationVisible()?__("yes"):__("no") ; ?>
      </td>
    </tr>
    <tr>
      <th><label><?php echo __('Sampling location code');?></label></th>
      <td id="specimen_gtu_ref_code"><?php echo $spec->getGtuCode() ; ?></td>
    </tr>
    <tr>
      <th><label><?php echo __('Latitude');?></label></th>
      <td id="specimen_gtu_ref_lat"><?php echo $spec->Gtu->getLatitude() ; ?></td>
    </tr>
    <tr>
      <th><label><?php echo __('Longitude');?></label></th>
      <td id="specimen_gtu_ref_lon"><?php echo $spec->Gtu->getLongitude(); ?></td>
    </tr>
    <tr>
      <th class="top_aligned">      
        <?php echo __("Sampling location Tags") ?>
      </th>
      <td>
        <div class="inline">
          <?php echo html_entity_decode($spec->Gtu->getTagsWithCode(true)); ?>          
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2" id="specimen_gtu_ref_map"></td>
    </tr>
  </tbody>
</table>
