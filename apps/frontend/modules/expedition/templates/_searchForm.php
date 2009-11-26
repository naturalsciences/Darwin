<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form action="" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <?php echo $form->renderGlobalErrors() ?>
  <table>
    <thead>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <th><?php echo $form['from_date']->renderLabel(); ?></th>
        <th><?php echo $form['to_date']->renderLabel(); ?></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <?php echo $form['name']->renderError() ?>
        </td>
        <td>
          <?php echo $form['from_date']->renderError() ?>
        </td>
        <td>
          <?php echo $form['to_date']->renderError() ?>
        </td>
        <td>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo $form['name']->render() ?>
        </td>
        <td>
          <?php echo $form['from_date']->render() ?>
        </td>
        <td>
          <?php echo $form['to_date']->render() ?>
        </td>
        <td>
          <input type="submit" value="<?php echo __('Search'); ?>" />
        </td>
      </tr>
    </tbody>
  </table>
</form>