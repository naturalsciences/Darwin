<?php $read_only = (isset($view)&&$view)?true:false ; ?>
<?php if ($read_only) : ?> 
  <?php foreach($form['Comments'] as $form_value):?>      
    <fieldset class="opened"><legend><b><?php echo __('Notion');?></b> : <?php echo $form_value['notion_concerned']->getValue();?></legend>
    <?php echo $form_value['comment']->getValue() ;?>
    </fieldset>
  <?php endforeach;?>
<?php else : ?>
<table class="property_values comments"  id="spec_ident_comments">
    <thead style="<?php echo ($form['Comments']->count() || $form['newComments']->count())?'':'display: none;';?>" class="spec_ident_comments_head">
    <tr>   
      <th><?php echo __('Notion');?></th>
      <th><?php echo __('Comment');?></th>
      <th><?php echo $form['comment'];?></th>
    </tr>
  </thead>
  <?php $retainedKey = 0;?>
  <?php foreach($form['Comments'] as $form_value):?>   
     <?php include_partial('specimen/spec_comments', array('form' => $form_value, 'rownum'=>$retainedKey));?>
     <?php $retainedKey = $retainedKey+1;?>
  <?php endforeach;?>
  <?php foreach($form['newComments'] as $form_value):?>
     <?php include_partial('specimen/spec_comments', array('form' => $form_value, 'rownum'=>$retainedKey));?>
     <?php $retainedKey = $retainedKey+1;?>
  <?php endforeach;?>          
   <tfoot>
     <tr>
       <td colspan="3">
         <div class="add_comments">
           <a href="<?php echo url_for('specimen/addComments'.($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_comment"><?php echo __('Add comment');?></a>
         </div>
       </td>
     </tr>
   </tfoot>
</table>
 
<script  type="text/javascript">
$(document).ready(function () {

    $('#add_comment').click( function()
    {
        parent = $(this).closest('table.comments');
        parentId = $(parent).attr('id');
        $.ajax(
        {
          type: "GET",
          url: $(this).attr('href')+ ($('table#'+parentId+' tbody.spec_ident_comments_data').length),
          success: function(html)
          {                    
            $(parent).append(html);
          }
        });
        $(this).closest('table.comments').find('thead').show();
        return false;
    }); 
});
</script>
<?php endif ; ?>
