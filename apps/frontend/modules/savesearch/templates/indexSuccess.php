<?php slot('title', __('My saved searches'));  ?>
<?php use_helper('Date');?>
<div class="page">
  <h1><?php echo __('My saved searches');?></h1>
  <table class="saved_searches">
  <?php foreach($searches as $search):?>
    <tr class="r_id_<?php echo $search->getId();?>">
        <td class="fav_col"><?php if($search->getFavorite()):?>
            <?php echo image_tag('favorite_on.png', 'alt=Favorite class=fav_img');?>
        <?php else:?>
            <?php echo image_tag('favorite_off.png', 'alt=Favorite class=fav_img');?>
        <?php endif;?>
        </td>
        <td>
          <div class="search_name">
            <?php echo link_to($search->getName(),'specimensearch/search?search_id='.$search->getId(),array('title'=>__('Go to your search')) ); ?>
          </div>
          <div class="date">
            <?php echo format_datetime($search->getModificationDateTime(),'f');?>
          </div>
        </td>
        <td><?php echo link_to(image_tag('criteria.png'),'specimensearch/index?search_id='.$search->getId());?></td>
        <td><a href="" class="edit_request"><?php echo image_tag('edit.png');?></a></td>
        <td>
         <?php echo link_to(image_tag('remove.png'), 'savesearch/deleteSavedSearch?table=my_saved_searches&id='.$search->getId(), array('confirm' =>  __('Are you sure ?')));?>
        </td>
    </tr>
<?php endforeach;?>
    </table>
</div>

<script type="text/javascript">

$(document).ready(function () {
  if($('#my_saved_searches_favorite').attr('checked') == 'checked')
  {
    $('#favorite_on').attr('class','show') ;
    $('#favorite_off').attr('class','hidden') ;      
  }
  else
  {
    $('#favorite_on').attr('class','hidden') ;  
    $('#favorite_off').attr('class','show') ;      
  }

  $('#favorite_on').click(function(){
    $('#favorite_off').attr('class','show') ; 
    $(this).attr('class','hidden') ;
    $('#my_saved_searches_favorite').attr('checked','');
  });

  $('#favorite_off').click(function(){
    $('#favorite_on').attr('class','show') ; 
    $(this).attr('class','hidden') ;
    $('#my_saved_searches_favorite').attr('checked','checked');
  });

  $(".edit_request").click(function(){
    $(this).qtip({
        content: {
            title: { text : '<?php echo __('Edit your search')?>', button: 'X' },        
            url: '<?php echo url_for('savesearch/saveSearch');?>/id/' + getIdInClasses($(this).closest('tr')),
            method: 'get'
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'topMiddle', // instead of center, to prevent bad display when the qtip is too big
            adjust:{
              y: 150 // option set in case of the qtip become too big
            },
        },
        hide: false,
        style: {
            width: { min: 620, max: 800},
            border: {radius:3},
            title: { background: '#5BABBD', color:'white'}
        },
        api: {
            beforeShow: function()
            {
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
            $(this.elements.target).qtip("destroy");
            location.reload();
         }
         }
    });
    return false;
 });
}); 
</script>
