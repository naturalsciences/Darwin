<?php slot('title', __('Search Specimens'));  ?>  

<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'specimensearch')); ?>      
<div class="encoding">
<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
  <div class="page" id="search_div">
    <h1 id="title"><?php echo __('Specimens Search');?></h1>
    <form id="specimen_filter" class="specimensearch_form" action="<?php echo url_for('specimensearch/search');?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
      <div class="panel encod_screen" id="intro">
        <?php include_partial('widgets/screen', array(
          'widgets' => $widgets,
          'category' => 'specimensearchwidget',
          'columns' => 2,
          'options' => array('form' => $form),
        )); ?>  
        <p class="clear"> </p>
        <p class="form_buttons">
          <div class="check_right">
            <?php echo $form['col_fields'];?>
            <?php echo $form['rec_per_page']->render(array('class'=>'hidden'));?>
            <input type="submit" name="submit" id="submit" value="<?php echo __('Search'); ?>" class="search_submit">
          </div>
      </div>      
      <?php include_partial('savesearch/savebut');?>
    </form>
  </div>
</div>
