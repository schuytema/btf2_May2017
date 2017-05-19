<?php
	$user_info = $this->ion_auth->user()->row();
	$group_info = $this->m_btf2_interest_groups->get_interest_group($group_id);
?>
      <div class="starter-template">
				<a href="<?php echo base_url().'main/interest_groups/'.$group_id;?>" style="color:#333">
      		<h1 style="text-align:center"><?php echo $group_info['Name']; ?></h1>
				</a>
      	<h3>Invite a Friend to Your <?php echo $user_info->interest_group_name;?></h3>
        <?php
        	echo '<form action="'.base_url().'main/send_group_invite_email" method="post">';
        ?>
          <div class="form-group">
            <label for="Email">Enter their email</label>
          	<input type="text" class="form-control" id="Email" name="Email" value="">
          </div>
          <input type="hidden" name="User_Id" value="<?php echo $user_info->id; ?>">
          <input type="hidden" name="Group_Id" value="<?php echo $group_info['PK_Interest_Group_Id']; ?>">
          <button type="submit" class="btn btn-primary">Send Invite Email</button>
        </form>



      </div>
