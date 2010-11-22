<div class="login">
    <?php echo __('This application requires an authentication.');?>
    <?php echo form_tag('account/login');?>
      <table>
        <tbody>
          <tr>
            <td colspan="2">
              <?php echo $form->renderGlobalErrors() ?>
            </td>
          </tr>
          <tr>
            <th><?php echo $form['username']->renderLabel();?></th>
            <td>
              <?php echo $form['username']->renderError();?>
              <?php echo $form['username'];?>
            </td>
          </tr>
          <tr>
            <th><?php echo $form['password']->renderLabel();?></th>
            <td>
              <?php echo $form['password']->renderError();?>
              <?php echo $form['password'];?>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2"><input type="submit" value="<?php echo __('Log in');?>" /></td>
          </tr>
        </tfoot>
      </table>
    </form>
</div>
