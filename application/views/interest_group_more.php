<?php
$groups = $this->m_btf2_interest_groups->get_all_interest_groups($user_info->id);
if($groups!=null)
{
 ?>
  <div style="max-height:40vh; overflow-x: hidden; overflow-y:scroll;">
    <?php
    foreach ($groups as $element)
    {
      if($this->m_btf2_interest_groups->is_group_member($element['PK_Interest_Group_Id'], $user_info->id))
      {?>
        <div style="text-align:left;padding:0 3% 8px 3%;">
          <a href="<?php echo base_url().'main/interest_groups/'.$element['PK_Interest_Group_Id'];?>" style="display:inline-block"><?php echo $element['Name'];?></a><?php
          if($user_info->id == $element['FK_User_Id'])
          {
            $interest_group_name = $user_info->interest_group_name;
            $confirm = 'Are you sure you want to delete this '.$interest_group_name.'?\\r';
            $confirm.= '(All contents will be removed and the action cannot be reversed)';?>
            <a onclick="return confirm('<?php echo $confirm;?>');" href="<?php echo base_url().'main/delete_group/'.$element['PK_Interest_Group_Id'];?>" style="display:inline-block;float:right;" type="button">
              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </a><?php
          }?>
          </br>
          <?php echo $element['Description'];?>
        </div><?php
      }
    }
    echo '<span style="float:left;padding-left:3%;margin-top:8px;"><b>Explore other '.$user_info->interest_group_name.'s</b></span></br></br>';
    foreach ($groups as $element)
    {
      if(!$this->m_btf2_interest_groups->is_group_member($element['PK_Interest_Group_Id'], $user_info->id))
      {?>
        <div style="text-align:left;padding:0 3% 8px 3%;">
          <a href="<?php echo base_url().'main/interest_groups/'.$element['PK_Interest_Group_Id'];?>" style="display:inline-block"><?php echo $element['Name'];?></a><?php
          if($user_info->id == $element['FK_User_Id'])
          {
            $interest_group_name = $user_info->interest_group_name;
            $confirm = 'Are you sure you want to delete this '.$interest_group_name.'?\\r';
            $confirm.= '(All contents will be removed and the action cannot be reversed)';?>
            <a onclick="return confirm('<?php echo $confirm;?>');" href="<?php echo base_url().'main/delete_group/'.$element['PK_Interest_Group_Id'];?>" style="display:inline-block;float:right;" type="button">
              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </a><?php
          }?>
          </br>
          <?php echo $element['Description'];?>
        </div><?php
      }
    }
    ?>
  </div>
  <?php
} else {
  ?>
  <p><i>No groups to show. Add the first group</i></p>
  <?php
} ?>
