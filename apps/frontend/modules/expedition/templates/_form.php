<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form class="edition" action="<?php echo url_for('expedition/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expedition_from_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['expedition_from_date']->renderError() ?>
          <?php echo $form['expedition_from_date'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expedition_to_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['expedition_to_date']->renderError() ?>
          <?php echo $form['expedition_to_date'] ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <table class="encoding collections_rights" id="exp_member_table">
            <thead>
              <tr>
                <th>&nbsp;</th>
                <th><label><?php echo __("Members") ; ?></label></th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
                <?php $retainedKey = 0;?>
              <?php foreach($form['Members'] as $form_value):?>
                <?php include_partial('member_row', array('form' => $form_value, 'ref_id' => ($form->getObject()->isNew() ? '':$form->getObject()->getId()), 'row_num'=>$retainedKey));
        $retainedKey++;?>
              <?php endforeach;?>
              <?php foreach($form['newMember'] as $form_value):?>
                <?php include_partial('member_row', array('form' => $form_value, 'ref_id' => ($form->getObject()->isNew() ? '':$form->getObject()->getId()), 'row_num'=>$retainedKey));
        $retainedKey++;?>
              <?php endforeach;?>
            </tbody>
          </table>
          <div class='add_value'>
            <a href="<?php echo url_for('expedition/addMember'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" class="hidden"></a>
            <a class='add_member' href="<?php echo url_for('people/choose');?>"><?php echo __('Add Member');?></a>
          </div>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form['id'] ?>

          <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to(__('New Expedition'), 'expedition/new') ?>
          <?php endif?>

          &nbsp;<a href="<?php echo url_for('expedition/index') ?>"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to(__('Delete'), 'expedition/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure?'))) ?>
          <?php endif; ?>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>

<script  type="text/javascript">

function forceCollectorsHelper(e,ui)  
{
   $(".ui-state-highlight").html("<td colspan='3' style='line-height:"+ui.item[0].offsetHeight+"px'>&nbsp;</td>");  
}
function reOrderCollectors(el) 
{
  $(el).find('tr:visible').each(function (index, item){
    $(item).find('input[id$=\"_order_by\"]').val(index+1);
  });
}

$(document).ready(function () {

   $("#exp_member_table tbody").sortable({
     placeholder: 'ui-state-highlight',
     handle: '.spec_ident_collectors_handle',
     axis: 'y',
     change: function(e, ui) {
              forceCollectorsHelper(e,ui);
            },
     deactivate: function(event, ui) {
                  reOrderCollectors(this);
                }
    }); 
});

$(document).ready(function () {
  //});

var ref_element_id = null;
var ref_element_name = null;
fct_update = addMember;
 $("a.add_member").click(function(){
    $(this).qtip({
        content: {
            title: { text : 'Choose a Member', button: 'X' },
            url: $(this).attr('href')
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'center' // ...at the center of the viewport
        },
        hide: false,
        style: {
            width: { min: 876, max: 1000},
            border: {radius:3},
            title: { background: '#5BABBD', color:'white'}
        },
        api: {
            beforeShow: function()
            {
                // Fade in the modal "blanket" using the defined show speed
              ref_element_id = null;
              ref_element_name = null;
              addBlackScreen()
              $('#qtip-blanket').fadeIn(this.options.show.effect.length);
            },
            beforeHide: function()
            {
                // Fade out the modal "blanket" using the defined hide speed
                $('#qtip-blanket').fadeOut(this.options.hide.effect.length).remove();
            },
         onHide: function()
         {
            $('.result_choose_collector').die('click') ;
            $(this.elements.target).qtip("destroy");
         }
         }
    });
    return false;
 });
});

function addMember(people_ref, people_name)
{ 
  $.ajax(
  {
    type: "GET",
    url: $('.add_value a.hidden').attr('href')+ (0+$('#exp_member_table tbody tr').length)+'/people_ref/'+people_ref + '/iorder_by/' + (0+$('#exp_member_table tbody tr').length),
    success: function(html)
    {
      $('#exp_member_table tbody').append(html);
      reOrderCollectors($('#exp_member_table'));
      //$('#exp_member_table tbody tr:last').attr("id" , user_ref) ;
    }
  });
  return false;
}

</script>