<?php if(isset($pagerLayout) && isset($form['rec_per_page'])): ?>
    <div class="pager paging_info">
      <table>
        <tr>
          <td><?php echo image_tag('info2.png');?></td>
          <td><?php $recTotal = $pagerLayout->getPager()->getNumResults(); $recordLabel = ($recTotal > 1)?'records':'record'; echo __('Your query retrieved').'&nbsp;'.$recTotal.'&nbsp;'.__($recordLabel);?></td>
          <td><ul><li><?php echo $form['rec_per_page']->renderLabel(); echo $form['rec_per_page']->render(); ?></li></ul></td>
        </tr>
      </table>
    </div>
<?php endif; ?>