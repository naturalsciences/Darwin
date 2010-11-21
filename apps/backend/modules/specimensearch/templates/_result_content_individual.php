<td class="col_individual_type">
  <?php if($specimen->getIndividualTypeGroup() != 'specimen') : ?>
    <?php echo $specimen->getIndividualTypeGroup();?>
  <?php endif ; ?>
</td>
<td class="col_sex"><?php echo ucfirst(($specimen->getIndividualSex()=='undefined')?'':$specimen->getIndividualSex());?></td> 
<td class="col_state"><?php echo ucfirst(($specimen->getIndividualState()=='not applicable')?'':$specimen->getIndividualState());?></td> 
<td class="col_stage"><?php echo ucfirst(($specimen->getIndividualStage()=='undefined')?'':$specimen->getIndividualStage());?></td> 
<td class="col_social_status"><?php echo ucfirst(($specimen->getIndividualSocialStatus()=='not applicable')?'':$specimen->getIndividualSocialStatus());?></td> 
<td class="col_rock_form"><?php echo ucfirst(($specimen->getIndividualRockForm()=='not applicable')?'':$specimen->getIndividualRockForm());?></td> 
<td class="col_individual_count">
  <?php if($specimen->getIndividualCountMin() != $specimen->getIndividualCountMax()):?>
    <?php echo $specimen->getIndividualCountMin() . ' - '.$specimen->getIndividualCountMax();?>
  <?php else:?>
    <?php echo $specimen->getIndividualCountMin();?>
  <?php endif;?>
</td>
