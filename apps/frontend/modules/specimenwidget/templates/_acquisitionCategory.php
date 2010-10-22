<table>
  <tbody>
    <?php if($form['acquisition_category']->hasError() || $form['acquisition_date']->hasError()):?>
      <tr>
        <td colspan="2">
          <?php echo $form['acquisition_category']->renderError(); ?>
          <?php echo $form['acquisition_date']->renderError(); ?>
        <td>
      </tr>
    <?php endif; ?>
    <tr>
      <th>
        <?php echo $form['acquisition_category']->renderLabel(); ?>
      </th>
      <td>
        <?php if(isset($view) && $view) : ?>
          <?php echo $form->getObject()->getAcquisitionCategory() ; ?>
        <?php else  : ?>
          <?php echo $form['acquisition_category']->render(); ?>
        <?php endif ; ?>
      </td>
    </tr>
    <tr>
      <th>
        <?php echo $form['acquisition_date']->renderLabel() ?>
      </th>
      <td>
        <?php if(isset($view) && $view) : ?>
          <?php if (FuzzyDateTime::getDateTimeStringFromArray($form->getObject()->getAcquisitionDate()) != '0001/01/01') : ?>        
            <?php echo FuzzyDateTime::getDateTimeStringFromArray($form->getObject()->getAcquisitionDate()) ?>
          <?php else : ?>
          -
          <?php endif ; ?>
        <?php else  : ?>
          <?php echo $form['acquisition_date']->render() ?>
        <?php endif ; ?>
      </td>
    </tr>
  </tbody>
</table>
