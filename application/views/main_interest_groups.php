<?php
$user_info = $this->ion_auth->user()->row();
$group_member = true;
if($group_id==0)
{
  $group = $this->m_btf2_interest_groups->get_default_interest_group($user_info->default_interest_group, $user_info->id);
} else {
  $group = $this->m_btf2_interest_groups->get_interest_group($group_id);
  if(!$this->m_btf2_interest_groups->is_group_member($group['PK_Interest_Group_Id'],$user_info->id))
  {
    $group_member = false;
  }
}
if (isset($_SESSION['time']))
{
  $time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['time'])))));
} else {
  $query = $this->db->query("SELECT last_login FROM users WHERE id = $user_info->id");
  if ($query->num_rows())
  {
    $last_login = $query->result_array();
    $last_login = $last_login[0]['last_login'];
  }
  $_SESSION['last_login'] = $last_login;
  $time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['last_login'])))));
}
?>
<div class="starter-template">
  <?php
  if($group != NULL)
  {
    if(!$group_member)
    {?>
      <a role="button" class="btn btn-primary btn-xs" href="<?php echo base_url().'main/join_group/'.$group['PK_Interest_Group_Id'].'/'.$user_info->id;?>" style="float:right;z-index:2;margin-top:-30px;">
        Join
      </a><?php
    } else if(!$this->m_btf2_interest_groups->is_default_group($group['PK_Interest_Group_Id'], $user_info->id)) {?>
      <a role="button" class="btn btn-primary btn-xs" href="<?php echo base_url().'main/make_default_group/'.$group['PK_Interest_Group_Id'].'/'.$user_info->id;?>" style="float:right;margin-top:-30px;">
        Set Default
      </a><?php
    }
    echo '<span style="font-size:2em;">'.$group['Name'].'</span>';
    echo '<p>'.$group['Description'].'</p>';
    ?>
    <div class="container" style="width:100%;margin-top:5px;">
      <ul class="nav nav-tabs">
        <li <?php if($tab == 'feed') {echo 'class="active"';}?>><a data-toggle="tab" href="#feed" style="padding:7px;">Feed</a></li>
        <li <?php if($tab == 'people') {echo 'class="active"';}?>><a data-toggle="tab" href="#people" style="padding:7px;">People</a></li>
        <li <?php if($tab == 'projects') {echo 'class="active"';}?>><a data-toggle="tab" href="#projects" style="padding:7px;">Projects</a></li>
        <li <?php if($tab == 'more') {echo 'class="active"';}?>><a data-toggle="tab" href="#more" style="padding:7px;">More Groups</a></li>
      </ul>
      <div class="tab-content">
        <div id="feed" class="tab-pane fade <?php if($tab == 'feed') {echo 'in active';}?>">
          </br>
          <?php
          if($group_member)
          {
            if($this->m_btf2_interest_groups->is_group_admin($group['PK_Interest_Group_Id'], $user_info->id))
            {
              $link = base_url().'main/add_content_to_group/'.$group['PK_Interest_Group_Id'];
            } else {
              $group_id = $group['PK_Interest_Group_Id'];
              $admin = $this->db->query("SELECT FK_User_Id FROM btf2_interest_group WHERE PK_Interest_Group_Id = '$group_id'");
              $admin = $admin->result_array()[0]['FK_User_Id'];
              $admin_email = $this->db->query("SELECT email FROM users WHERE id = $admin");
              $admin_email = $admin_email->result_array()[0]['email'];
              $link = 'mailto:'.$admin_email;
            }
            ?>
            <a role="button" class="btn btn-primary btn-sm" href="<?php echo $link;?>">
              Add Content
            </a>
            </br></br>
            <?php include 'interest_group_feed.php';
          } else {
            echo 'You are not a member of this group. To view its content, request to join.';?>
            </br>
          <?php
          } ?>
        </div> <!-- End #feed -->
        <div id="people" class="tab-pane fade <?php if($tab == 'people') {echo 'in active';}?>">
          <?php if($group_member)
          {?>
            </br>
            <a role="button" class="btn btn-primary btn-sm" href="<?php echo base_url().'main/group_invite_email/'.$group['PK_Interest_Group_Id'];?>" style="float:left;display:inline-block;margin-left:2%;">
              Invite Friends
            </a>
            <a href="<?php echo base_url().'main/leave_group/'.$group['PK_Interest_Group_Id'];?>" role="button" class="btn btn-primary btn-sm" style="float:right;display:inline-block;margin-right:2%;">
              Leave <?php echo $user_info->interest_group_name; ?>
            </a><?php
          }?>
          </br>
          <?php include 'interest_group_people.php';?>
        </div> <!-- End #people -->
        <div id="projects" class="tab-pane fade <?php if($tab == 'projects') {echo 'in active';}?>">
          </br>
          <?php include 'interest_group_projects.php';?>
        </div> <!-- End #projects -->
        <div id="more" class="tab-pane fade <?php if($tab == 'more') {echo 'in active';}?>">
          </br>
          <a href=<?php echo '"'.base_url().'main/create_interest_group"';?> role="button" class="btn btn-primary btn-sm" style="float:left;display:inline-block;margin-left:2%;">
            Add <?php echo $user_info->interest_group_name;?>
          </a>
          <a role="button" class="btn btn-primary btn-sm" href="<?php echo base_url().'main/group_invite/1';?>" style="display:inline-block;float:right;margin-right:2%;">
            Enter Invite Code
          </a>
          </br></br>
          <?php include 'interest_group_more.php';?>
        </div> <!-- End #more -->
      </div> <!-- End .tab-content -->
    </div> <!-- End .container -->
    <?php
  } else {?>
    <p>You are not a member of any group yet. Join one!</p>
    </br>
    <a href=<?php echo '"'.base_url().'main/create_interest_group"';?> role="button" class="btn btn-primary">Add Interest Group</a>
    </br></br>
    <?php include 'interest_group_more.php';
  } ?>
</div>
