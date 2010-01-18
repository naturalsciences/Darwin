<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue_expedition','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>
<?php slot('title', __('Edit Expedition'));  ?>
<div class="page">
    <h1 class="edit_mode"><?php echo __('Edit Expedition');?></h1>

    <?php include_partial('form', array('form' => $form)) ?>

    <script type="text/javascript">
      var chgstatus_url='<?php echo url_for('widgets/changeStatus?category=catalogue_expedition');?>';
      var chgorder_url='<?php echo url_for('widgets/changeOrder?category=catalogue_expedition');?>';
      var reload_url='<?php echo url_for('widgets/reloadContent?category=catalogue_expedition&eid='.$form->getObject()->getId());?>';
    </script>
    <ul class="board_col one_col encod_screen">
      <?php foreach($widgets as $id => $widget):?>
        <?php if(!$widget->getVisible()) continue;?>
        <?php include_partial('widgets/wlayout', 
                              array('widget' => $widget->getGroupName(),
                                    'is_opened' => $widget->getOpened(),
                                    'category' => 'cataloguewidget',
                                    'options' => array('eid' => $form->getObject()->getId(), 
                                                       'table' => 'expeditions'
                                                      )
                                   )
                             ); ?>
      <?php endforeach;?>
    </ul>

</div>