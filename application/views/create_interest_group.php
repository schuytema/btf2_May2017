<?php
	$user_info = $this->ion_auth->user()->row();
?>
			<div class="starter-template">
        <?php
        echo '<form action="'.base_url().'main/process_add_interest_group" method="post">';
        ?>
					<div class="form-group">
						<label for="Name">Group Name</label>
	          <input type='text' class="form-control" name="Name" id="Name"/>
	        </div>
	        <div class="form-group">
	          <label for="Description">Description</label>
	          <textarea class="form-control" rows="2" id="Description" name="Description"></textarea>
	        </div>
					<input type="hidden" name="FK_User_Id" value="<?php echo set_value('FK_User_Id', $user_info->id); ?>">
	        <button type="submit" class="btn btn-primary">Create Interest Group</button>
	      </form>
			</div>
