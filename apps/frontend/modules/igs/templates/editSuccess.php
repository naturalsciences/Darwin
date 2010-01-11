<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'catalogue_igs','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>
<?php slot('title', __('Edit RBINS I.G. number'));  ?>
<div class="page">
    <h1 class="edit_mode"><?php echo __('Edit I.G.');?></h1>

    <?php include_partial('form', array('form' => $form)) ?>

    <script type="text/javascript">
      var chgstatus_url='<?php echo url_for('widgets/changeStatus?category=catalogue_igs');?>';
      var chgorder_url='<?php echo url_for('widgets/changeOrder?category=catalogue_igs');?>';
      var reload_url='<?php echo url_for('widgets/reloadContent?category=catalogue_igs&eid='.$form->getObject()->getId());?>';
    </script>
    <ul class="board_col one_col encod_screen">
      <?php foreach($widgets as $id => $widget):?>
        <?php if(!$widget->getVisible()) continue;?>
        <?php include_partial('widgets/wlayout', 
                              array('widget' => $widget->getGroupName(),
                                    'is_opened' => $widget->getOpened(),
                                    'category' => 'cataloguewidget',
                                    'options' => array('eid' => $form->getObject()->getId(), 
                                                       'table' => 'igs'
                                                      )
                                   )
                             ); ?>
      <?php endforeach;?>
    </ul>

</div>