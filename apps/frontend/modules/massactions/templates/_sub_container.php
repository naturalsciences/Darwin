  <table id="sub_form_<?php echo $mAction;?>">
    <tr>
      <th>
        <?php echo $form['MassActionForm']['container']['container']->renderLabel();?>
      </th>
      <td>
        <?php echo $form['MassActionForm']['container']['container']->renderError();?>
        <?php echo $form['MassActionForm']['container']['container'];?>
      </td>
    </tr>
  </table>

  <script  type="text/javascript">
  $(document).ready(function () {
      changeSubmit(true);
  });
  </script>