<?php
$interest_group_members = $this->m_btf2_interest_groups->pull_group_members($group['PK_Interest_Group_Id']);?>

<div class="panel-body">

<?php
if($group_member)
{
  $user_info_temp = $this->m_btf2_users->get_user_info_from_id($user_info->id);?>
  <img src="<?php echo base_url(); ?>user_mugs/<?php echo $user_info_temp['mug']; ?>" class="img-circle" style="border: 1px solid #d3d3d3;" height="40" width="40">
  <h4 style="display:inline-block; padding-top:7px"><a href=<?php echo '"' . base_url() . 'main/user_profile/' . $user_info->id . '">' . $user_info_temp['first_name'] . ' ' . $user_info_temp['last_name'];?></a></h4>
  <?php
  $group_id = $group['PK_Interest_Group_Id'];
  echo '<hr>';
  echo '</br>';
}
$is_admin = $this->m_btf2_interest_groups->is_group_admin($group['PK_Interest_Group_Id'],$user_info->id);
foreach ($interest_group_members as $member)
{
  if($member['FK_User_Id']!=$user_info->id)
  {
    $user_info_temp = $this->m_btf2_users->get_user_info_from_id($member['FK_User_Id']);?>
    <div style="display:block;text-align:left;margin-bottom:10px;">
      <img src="<?php echo base_url(); ?>user_mugs/<?php echo $user_info_temp['mug']; ?>" class="img-circle" style="border: 1px solid #d3d3d3;float:left;margin-right:10px;" height="40" width="40" align="middle;"/>
      <a href="<?php echo base_url() . 'main/user_profile/' . $member['FK_User_Id'];?>"><?php echo $user_info_temp['first_name'] . ' ' . $user_info_temp['last_name'];?></a>
      <?php
      if($this->m_btf2_interest_groups->is_group_admin($group['PK_Interest_Group_Id'],$user_info_temp['id']))
      {
        if($user_info->id == $group['FK_User_Id'])
        {
          echo ' (<i>Admin</i>)</br>';
          echo '<a href="'.base_url().'main/remove_group_admin/'.$group['PK_Interest_Group_Id'].'/'.$user_info_temp['id'].'">';
            echo 'Remove Admin';
          echo '</a>';
        } else {
          echo '</br>';
          echo '(<i>Admin</i>)';
        }
      } else if($is_admin) {
        echo '</br>';
        echo '<a href="'.base_url().'main/make_group_admin/'.$group['PK_Interest_Group_Id'].'/'.$user_info_temp['id'].'">';
          echo 'Make Admin';
        echo '</a>';
      } else {
        echo '</br></br>';
      }?>
    </div>
    <?php
  }
}

echo '</div>';
?>
