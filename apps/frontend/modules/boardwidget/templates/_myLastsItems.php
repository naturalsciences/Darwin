<?php slot('widget_title',__('AMy Lasts Items'));  ?>

<?php if(count($items)):?>
<table class="show_table">
        <thead>
	  <tr>
            <th>
                <?php echo __('Date');?>
            </th>
            <th class="referenced_relation">
                <?php echo __('Relation');?>
            </th>
            <th class="record_id">
                <?php echo __('Record Id');?>
            </th>
	    <th></th>
          </tr>
        </thead>
    <tbody>
      <?php foreach($items as $item):?>
	<tr>
	  <td><?php $date = new DateTime($item['modification_date_time']);
		echo $date->format('d M Y ( a )'); ?></td>
	  <td><?php echo $item['referenced_relation'];?></td>
	  <td><?php if($item['action'] != 'delete' && $item->getLink() != ''):?>
		    <?php echo link_to($item['record_id'], $item->getLink());?>
		  <?php else:?>
		    <?php echo $item['record_id'];?>
		  <?php endif;?>
	  </td>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
<br />
  <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>

  <script language="javascript" type="text/javascript"> 
  $(function () {
     $('#myLastsItems .pager_nav a').click(function(event)
     {
	event.preventDefault();
	$.ajax({
	  url: $(this).attr('href'),
          success: function(html) {
	    $('#myLastsItems .widget_content').html(html);
	  }
	});
     });
  });
  </script>
<?php else:?>
  <?php echo __('Nothing here');?>
<?php endif;?>
<br />