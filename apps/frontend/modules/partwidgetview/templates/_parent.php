<?php if ($part->getParentRef() == "") : ?>
  <?php echo __("No parent") ; ?>
<?php else : ?>
<table class="catalogue_table_view">
  <tr>
    <td rowspan="<?php echo $Codes->count() ; ?>">
      <?php echo $part->Parent->getSpecimenPart() ?>
    </td>
    <td>
      <ul>
      <?php foreach($Codes as $code):?>
        <li><?php if($code->getCodeCategory() == "main") echo "<b>" ; ?>
          <?php echo $code->getCodePrefix();?>
          <?php echo $code->getCodePrefixSeparator();?>
          <?php echo $code->getCode();?>
          <?php echo $code->getCodeSuffixSeparator();?>
          <?php echo $code->getCodeSuffix();?>
          <?php if($code->getCodeCategory() == "main") echo "</b>" ; ?>
        </li>
      <?php endforeach ; ?>
      </ul>
    </td>
  </tr>
</table>  
<?php endif ; ?>
