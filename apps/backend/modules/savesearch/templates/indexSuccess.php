<?php use_helper('Date');?>
<div class="page">

  <?php if($is_only_spec):?>
    <?php slot('title', __('My saved specimens'));?>
    <h1><?php echo __('My saved specimens');?></h1>
  <?php else:?>
    <?php slot('title', __('My saved searches'));?>
    <h1><?php echo __('My saved searches');?></h1>
  <?php endif;?>

  <table class="saved_searches">
  <tbody>
  <?php foreach($searches as $search):?>
    <tr class="r_id_<?php echo $search->getId();?>">
        <td class="fav_col"><?php if($search->getFavorite()):?>
            <?php echo image_tag('favorite_on.png', 'alt=Favorite class="fav_img favorite_on"');?>
            <?php echo image_tag('favorite_off.png', 'alt=Favorite class="fav_img favorite_off hidden"');?>
        <?php else:?>
            <?php echo image_tag('favorite_on.png', 'alt=Favorite class="fav_img favorite_on hidden"');?>
            <?php echo image_tag('favorite_off.png', 'alt=Favorite class="fav_img favorite_off"');?>
        <?php endif;?>
        </td>
        <td>
          <div class="search_name">
            <?php echo link_to($search->getName(),'specimensearch/search?search_id='.$search->getId(),array('title'=>__('Go to your search')) ); ?>
            <?php if($is_only_spec):?>
              <span class="saved_count">(<?php echo format_number_choice('[0] No Items|[1] 1 Item |(1,+Inf] %1% Items', array('%1%' =>  $search->getNumberOfIds()), $search->getNumberOfIds());?>)</span>
            <?php endif;?>
          </div>
          <div class="date">
            <?php echo format_datetime($search->getModificationDateTime(),'f');?>
          </div>
        </td>
        <td><div class="source_flag"><?php echo __($search->getSubject());?></div></td>
        <td><?php if(!$is_only_spec):?><?php echo link_to(image_tag('criteria.png'),'specimensearch/index?search_id='.$search->getId());?><?php endif;?></td>
        <td><a href="" class="edit_request"><?php echo image_tag('edit.png');?></a></td>
        <td>
         <?php echo link_to(image_tag('remove.png'), 'savesearch/deleteSavedSearch?table=my_saved_searches&id='.$search->getId(), array('class'=>'del_butt'));?>
        </td>
    </tr>
<?php endforeach;?>
    </tbody>
    </table>
</div>

<script type="text/javascript">

$(document).ready(function () {

  $('.saved_searches .del_butt').click(function(event)
  {
    event.preventDefault();
    search_row = $(this).closest('tr');
    var answer = confirm('<?php echo __('Are you sure ?');?>');
    if( answer )
    {
      $.get($(this).attr('href'),function (html){
        search_row.remove();
      });
    }
  });

  $('.saved_searches .fav_img').click(function(){
    if($(this).hasClass('favorite_on'))
    {
      $(this).parent().find('.favorite_off').removeClass('hidden'); 
      $(this).addClass('hidden') ;
      fav_status = 0;
    }
    else
    {
      $(this).parent().find('.favorite_on').removeClass('hidden');
      $(this).addClass('hidden') ;
      fav_status = 1;
    }
    rid = getIdInClasses($(this).closest('tr'));
    $.get('<?php echo url_for('savesearch/favorite');?>/id/' + rid + '/status/' + fav_status,function (html){
    });
  });



  $(".edit_request").click(function(){
    scroll(0,0) ;  
    $(this).qtip({
        content: {
            title: { text : '<?php echo ($is_only_spec ? __('Edit your specimens') : __('Edit your search') ) ;?>', button: 'X' },        
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
