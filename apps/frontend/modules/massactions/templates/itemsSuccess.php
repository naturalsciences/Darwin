<?php if(count($items) !=0 ):?>
  <table>
    <?php foreach($items as $item):?>
      <tr>
        <td>
          <?php echo image_tag('info.png',"title=info class=extd_info");?>
          <div class="extended_info" style="display:none;">
            <?php //include_partial('extendedInfo', array('part' => $part, 'codes' => $codes) );?>
          </div>
        </td>
        <td>
          <?php echo $item->getTaxonName();?>
        </td>
        <td>
          <input name="mass_action[item_list][]" type="hidden" value="<?php echo $item->getSpecRef();?>" class="item_row">
          <a class="row_delete" href="#" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?></a>
        </td>
      </tr>
    <?php endforeach;?>
  </table>
  <script  type="text/javascript">
   $(document).ready(function () {
      $('.row_delete').click(function(event)
      {
        event.preventDefault();
        $(this).closest('tr').remove();
        checkItem();
      });
   });
  </script>
<?php else:?>
  <p class="warn_message"><?php echo __('No Items here. Please pin some items or another source to be able to do a mass action');?></p>
<?php endif;?>
