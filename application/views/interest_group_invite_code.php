<?php
	$user_info = $this->ion_auth->user()->row();
?>
      <div>
      	<h2><?php echo $user_info->interest_group_name;?> Invite</h2>
        <?php
        	echo $bad_invite_code;
        	echo '<form action="'.base_url().'main/process_group_invite" method="post">';
        ?>
          <div class="form-group">
            <label for="Invite_Code">Enter Your <?php echo $user_info->interest_group_name;?> Invite Code</label>
          	<input type="text" class="form-control" id="Invite_Code" name="Invite_Code" value="">
          </div>
          <button type="submit" class="btn btn-primary">Process Invite</button>
        </form>



      </div>
