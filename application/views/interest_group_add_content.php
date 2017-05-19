<?php
	$user_info = $this->ion_auth->user()->row();
	$group = $this->m_btf2_interest_groups->get_interest_group($group_id);
?>
			<div class="starter-template">
        <?php
				echo '<h1>'.$group['Name'].'</h1>';
        echo '<form action="'.base_url().'main/process_add_group_content/'.$group_id.'" method="post">';
        ?>
					<!--div class="form-group">
						<label for="Name">Group Name</label>
	          <input type='text' class="form-control" name="Name" id="Name"/>
	        </div-->
	        <div class="form-group">
	          <label for="Title">Title</label>
	          <input type="text" class="form-control" id="Title" name="Title" value="" placeholder="Enter title" />
	        </div>
	        <div class="form-group">
	          <label for="Title">Image</label>
	          <input type="text" class="form-control" id="Image" name="Image" value="" placeholder="Image URL" />
	          <span id="helpBlock" class="help-block">Enter a valid URL (or blank for none). Image should be 4x3 or other horizontal aspect ratio.</span>
	        </div>
	        <div class="form-group">
	          <label for="Content">Content</label>
	          <textarea class="form-control" rows="2" id="Content" name="Content"></textarea>
	        </div>
					<input type="hidden" name="FK_User_Id" value="<?php echo set_value('FK_User_Id', $user_info->id); ?>">
					<!--input type="hidden" name="FK_Interest_Group_Id" value="<?php echo set_value('FK_Interest_Group_Id', $group_id);?>"-->
	        <button type="submit" class="btn btn-primary">Add Content</button>
	      </form>
			</div>
