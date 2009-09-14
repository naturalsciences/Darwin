<?php slot('title', __('Add Specimens'));  ?>
<?php use_helper('Javascript') ?>
<?php echo javascript_tag("
var chgstatus_url='".url_for('widgets/changeStatus?category=specimen')."';
var chgorder_url='".url_for('widgets/changeOrder?category=specimen')."';
var reload_url='".url_for('widgets/reloadContent?category=specimen')."';

$(document).ready(function () {
    $('form').submit(function(event)
    {
        event.preventDefault();
//         console.log($('form').serialize());
        $('.error_fld').removeClass('error_fld');
        $('.spec_error_list').empty();
        var i = $.fn.qtip.interfaces.length; while(i--)
        {
            // Access current elements API
            var api = $.fn.qtip.interfaces[i];
            // Queue the animation so positions are updated correctly
            if(api && api.status.rendered && !api.status.hidden) api.destroy();
        };
        $.post($('form').attr('action'), $('form').serialize(),retrieve_spec_result,'json');
        return false;
    });
});


function retrieve_spec_result(data)
{
//     console.log(data);
    if(data[0]=='ok')
        alert('ok');
    else
    {
        for (var key in data)
        {
            $('.spec_error_list').append('<li>'+key+' : '+data[key]+'</li>');
            $('#specimen_'+key).addClass('error_fld');
            $('#specimen_'+key).qtip({
                content: data[key],
                show: { ready: true, when : { event: 'none'} },
                hide: { when: { event: 'change' } },
                style: { 
                    width: 200,
                    padding: 5,
                    background: '#ec9593',
                    color: 'black',
                    border: {
                        width: 7,
                        radius: 5,
                        color: '#c36b70'
                    },
                    tip: 'bottomLeft',
                    name: 'dark', // Inherit the rest of the attributes from the preset dark style
                },
                position: {
                    corner: {
                        target: 'topRight',
                        tooltip: 'bottomLeft'
                    }
                },
            });
        }
    }
}
");?>
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'specimen')) ?>
<div class="encoding">
    <?php echo image_tag('encod_left_disable.png','id="arrow_left" class="scrollButtons left"');?>
	<div class="page">
			<ul class="tabs">
				<li class="enabled selected" id="tab_0"> &lt; New Specimen &gt; </li>
				<li class="disabled" id="tab_1">Individuals</li>
				<li class="disabled" id="tab_2">Properties</li>
			</ul>
		<div class="tab_box" id="slider">
			<div class="scroll">
				<!-- the element that will be scrolled during the effect -->
				<div class="scrollContainer">
					<div class="panel" id="intro">
					<form action="<?php echo url_for('specimen/submit') ?>" method="POST">
					<div><ul class="spec_error_list"></ul>
					
  <ul class="board_col">
    <?php $changed_col=false;?>
    <?php foreach($widgets as $id => $widget):?>
      <?php if(!$widget->getVisible()) continue;?>

      <?php if($changed_col==false && $widget->getColNum()==2):?>
	    <?php $changed_col=true;?>
	      </ul>
	      <div class="board_spacer">&nbsp;</div>
	      <ul class="board_col">
      <?php endif;?>
	  <?php include_partial('widgets/wlayout', array(
            'widget' => $widget->getGroupName(),
            'is_opened' => $widget->getOpened(),
            'category' => 'specimenwidget')
	  ); ?>
    <?php endforeach;?>
    <?php if($changed_col==false):?>
      </ul>
      <div class="board_spacer">&nbsp;</div>
      <ul class="board_col">
      <?php endif;?>
  </ul>
  </div><br class="clear"/>
  <input type="submit" value="Submit" id="submit_spec_f1"/>
                    </form>
                    </div>
                    <div class="panel"> <a href="#" onclick="$('#submit').trigger('click');return false;">Click here</a></div>
					<div class="panel"> How it Works </div>
				</div>
			</div>
		</div>
	</div>
	<?php echo image_tag('encod_right_disable.png','id="arrow_right" class="scrollButtons right"');?>
</div>