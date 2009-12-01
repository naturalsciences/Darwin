<?php slot('title', __('Add Specimens'));  ?>

<script type="text/javascript">
var chgstatus_url='<?php echo url_for('widgets/changeStatus?category=specimen');?>';
var chgorder_url='<?php echo url_for('widgets/changeOrder?category=specimen');?>';
var reload_url='<?php echo url_for('widgets/reloadContent?category=specimen');?>';

$(document).ready(function ()
{
    $('.widget .widget_content:hidden .error_list:has(li)').each(function(){
        showWidgetContent($(this).closest('.widget'));
    });
    
    $('.spec_error_list li.hidden').each(function(){
        console.log($(this));
        field = getElInClasses($(this),'error_fld_');
        console.log(field);
        if( $('#specimen_'+field).length == 0 )
            $(this).show();
    });
});
</script>

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'specimen','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>
<div class="encoding">
    <?php echo image_tag('encod_left_disable.png','id="arrow_left" alt="Go Previous" class="scrollButtons left"');?>
	<div class="page">
		<ul class="tabs">
			<li class="enabled selected" id="tab_0"> <?php echo __('&lt; New Specimen &gt;');?> </li>
			<li class="disabled" id="tab_1"><?php echo __('Individuals');?></li>
			<li class="disabled" id="tab_2"><?php echo __('Properties');?></li>
		</ul>
 		<div class="panel encod_screen" id="intro">

            <?php include_stylesheets_for_form($form) ?>
            <?php include_javascripts_for_form($form) ?>

            <form action="<?php echo url_for('specimen/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
                <div>
                <?php //echo $form->renderHiddenFields() ?>
                 <?php echo $form['id']->render() ?>
                <?php if($form->hasErrors()):?>
                    <ul class="spec_error_list">
                        <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
                            <li><?php echo __($name." ".$error); ?></li>
                        <?php endforeach; ?>
                        <?php foreach ($form->getErrorSchema()->getErrors() as $name => $error): ?>
                            <li class="hidden error_fld_<?php echo $name;?>"><?php echo __($name." ".$error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif;?>
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
                            'category' => 'specimenwidget',
                            'options' => array('form' => $form),
                            )); ?>
                    <?php endforeach;?>
                        <?php if($changed_col==false):?>
                            </ul>
                            <div class="board_spacer">&nbsp;</div>
                            <ul class="board_col">
                        <?php endif;?>
                        </ul>
                    </div>
                    <p class="clear"></p>
                    <p>
                        <input type="submit" value="<?php echo __('Submit');?>" id="submit_spec_f1"/>
                    </p>
                </form>
            </div>
		</div>
	</div>
	<?php echo image_tag('encod_right_disable.png','id="arrow_right" alt="'.__('Go next').'" class="scrollButtons right"');?>