<?php slot('title', __('Search Specimens/Rocks/Minerals'));  ?>  

<h1>Specimen searchs List</h1>
<?php echo form_tag('search/search', array('class'=>'publicsearch_form'));?>
<div class="page">
  <h2 class="title"><?php echo __("Classifications") ?></h2>
  <div class="borded">
    <?php echo $form->renderHiddenFields(); ?>
    <table>
      <thead>
        <tr>
          <th></th>
          <th><?php echo __("Scientific Name") ?></th>  
          <th><?php echo __("Common Name") ?></th>  
          <th><?php echo __("Level") ?></th>  
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['taxon_name']->renderLabel();?> :</td>
          <td><?php echo $form['taxon_name'];?></td>  
          <td><?php echo $form['taxon_common_name'];?></td>  
          <td><?php echo $form['taxon_level_ref'];?></td>     
        </tr>   
        <tr>
          <td><?php echo $form['chrono_name']->renderLabel();?> :</td>
          <td><?php echo $form['chrono_name'];?></td>  
          <td><?php echo $form['chrono_common_name'];?></td>  
          <td><?php echo $form['chrono_level_ref'];?></td>     
        </tr> 
        <tr>
          <td><?php echo $form['litho_name']->renderLabel();?> :</td>
          <td><?php echo $form['litho_name'];?></td>  
          <td><?php echo $form['litho_common_name'];?></td>  
          <td><?php echo $form['litho_level_ref'];?></td>     
        </tr> 
        <tr>
          <td><?php echo $form['lithology_name']->renderLabel();?> :</td>
          <td><?php echo $form['lithology_name'];?></td>  
          <td><?php echo $form['lithology_common_name'];?></td>  
          <td><?php echo $form['lithology_level_ref'];?></td>     
        </tr> 
        <tr>
          <td><?php echo $form['mineral_name']->renderLabel();?> :</td>
          <td><?php echo $form['mineral_name'];?></td>  
          <td><?php echo $form['mineral_common_name'];?></td>  
          <td><?php echo $form['mineral_level_ref'];?></td>     
        </tr>                          
      </tbody>
    </table>
  </div>
  <table>
    <tbody>
      <tr>
        <td>
          <h2 class="title"><?php echo __("Collections") ?></h2>
          <div class="borded framed">
          <table class='double_table'>
            <tr>
              <td>
                <div class="treelist">
		              <?php echo $form['collection_ref'] ; ?>        
                </div>
                <div class="check_right">
                  <input type="button" class="result" value="clear" id="clear_collections">
                </div>
	            </td>
	          </tr>
          </table>
        </td>
        
        <td>
          <h2 class="title"><?php echo __("Countries") ?></h2>
          <div class="borded framed">
          <table id="gtu_search" class='double_table'>
            <thead>
              <tr><th colspan="2"><?php echo __('Tags') ; ?></th></tr>
            </thead>
            <tbody>              
              <?php foreach($form['Tags'] as $i=>$form_value):?>
                <?php include_partial('search/andSearch',array('form' => $form['Tags'][$i], 'row_line'=>$i));?>
              <?php endforeach;?>
              <tr class="and_row">
                <td colspan=2"><?php echo image_tag('add_blue.png');?><a href="<?php echo url_for('search/andSearch');?>" class="and_tag"><?php echo __('Add tag'); ?></a></td>
              </tr>
            </tbody>
          </table>
          </div>
        </td>  
      </tr>
    </tbody>
  </table>
  
  <table>
    <tbody>
      <tr>
        <td>
          <h2 class="title"><?php echo __("Types") ?></h2>
          <div class="borded framed" class='triple_table'>
            &nbsp;
          </div>
        </td>
        
        <td>
          <h2 class="title"><?php echo __("Sexes") ?></h2>
          <div class="borded framed" class='triple_table'>
            <?php echo $form['sex'] ; ?>
          </div>
        </td>

        <td>
          <h2 class="title"><?php echo __("Stages") ?></h2>
          <div class="borded framed" class='triple_table'>
            <?php echo $form['stage'] ; ?>
          </div>
        </td>          
      </tr>
    </tbody>
  </table>  
  <div class="check_right">
    <input type="submit" name="submit" id="submit" value="<?php echo __('Search'); ?>" class="search_submit">
  </div>
</div>  
</form>
<script type="text/javascript">
$(document).ready(function () {
    $('.treelist li:not(li:has(ul)) img.tree_cmd').hide();
    $('.collapsed').click(function()
    {
        $(this).hide();
        $(this).siblings('.expanded').show();
        $(this).parent().siblings('ul').show();
    });
    
    $('.expanded').click(function()
    {
        $(this).hide();
        $(this).siblings('.collapsed').show();
        $(this).parent().siblings('ul').hide();
    });
    $('.treelist li input[type=checkbox]').click(function()
    {
	    class_val = $(this).closest('li').attr('class');
   	  val = $(this).attr('checked') ;
	    alt_val = $(this).closest('ul .'+class_val).find(':checkbox').attr('checked',val);
    });    
    $('#clear_collections').click(function()
    {
  	  $('table.widget_sub_table').find(':checkbox').attr('checked','');    
    });   
  var num_fld = 1;
  $('.and_tag').click(function()
  {
    $.ajax({
      type: "GET",
      url: $(this).attr('href') + '/num/' + (num_fld++) ,
      success: function(html)
      {
        $('table#gtu_search > tbody .and_row').before(html);
      }
    });
    return false;
  });      

  $('input.tag_line').live('keydown click',purposeTags);

  function purposeTags(event)
  {
    if (event.type == 'keydown')
    {
      var code = (event.keyCode ? event.keyCode : event.which);
      if (code != 59 /* ;*/ && code != $.ui.keyCode.SPACE ) return;
    }        
    parent_el = $(this).closest('tr');

    if($(this).val() == '') return;
    $('.purposed_tags').html('<img src="/images/loader.gif" />');
    $.ajax({
      type: "GET",
      url: "<?php echo url_for('search/purposeTag');?>" + '/value/'+ $(this).val(),
      success: function(html)
      {
        parent_el.find('.purposed_tags').html(html);
        parent_el.find('.purposed_tags').show();
      }
    });
  }

  $('.purposed_tags li').live('click', function()
  {
    input_el = $(this).closest('tr').find('input.tag_line');
    if(input_el.val().match("\;\s*$"))
      input_el.val( input_el.val() + $(this).text() );
    else
      input_el.val( input_el.val() + " ; " +$(this).text() );
    input_el.trigger('click');
  });      
});  
</script>
