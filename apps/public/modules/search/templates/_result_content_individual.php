<td class="col_individual_type">
  <?php if($specimen->getIndividualTypeSearch() != 'specimen') : ?>
    <?php echo ucfirst($specimen->getIndividualTypeSearch());?>
  <?php endif ; ?>
</td>
<td class="col_sex">
  <?php echo ($specimen->getIndividualSex()=="undefined")?"":ucfirst($specimen->getIndividualSex()) ; ?>
</td>
<td class="col_state">
  <?php echo ($specimen->getIndividualState()=="not applicable")?"":ucfirst($specimen->getIndividualState());?>
</td> 
<td class="col_stage">
  <?php echo ($specimen->getIndividualStage()=="undefined")?"":ucfirst($specimen->getIndividualStage()) ; ?>
</td>
<td class="col_social_status">
  <?php echo ($specimen->getIndividualSocialStatus()=="not applicable")?"":ucfirst($specimen->getIndividualSocialStatus());?>
</td> 
<td class="col_rock_form">
  <?php echo ($specimen->getIndividualRockForm()=="not applicable")?"":ucfirst($specimen->getIndividualRockForm());?>
</td> 
<td class="col_individual_count right_aligned">
  <?php if($specimen->getIndividualCountMin() != $specimen->getIndividualCountMax()):?>
    <?php echo $specimen->getIndividualCountMin() . ' - '.$specimen->getIndividualCountMax();?>
  <?php else:?>
    <?php echo $specimen->getIndividualCountMin();?>
  <?php endif;?>
</td>
