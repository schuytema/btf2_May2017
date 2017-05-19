<style>
.bs-callout {
    padding: 20px;
    margin: 20px 0;
    border: 1px solid #eee;
    border-left-width: 5px;
    border-radius: 3px;
    line-height: 300%;
}
.bs-callout h4 {
    margin-top: 0;
    margin-bottom: 5px;
}
.bs-callout p:last-child {
    margin-bottom: 0;
}
.bs-callout code {
    border-radius: 3px;
}
.bs-callout+.bs-callout {
    margin-top: -5px;
}
.bs-callout-warning {
    border-left-color: #f0ad4e;
}
.bs-callout-warning h4 {
    color: #f0ad4e;
}
.bs-callout-success {
    border-left-color: #5cb85c;
}
.bs-callout-success h4 {
    color: #5cb85c;
}
</style>

<script>
function disableText()
{
  var box = document.getElementById("checkbox");
  var phone = document.getElementById("phone");
  var carrier = document.getElementById("phone_carrier");
  if(box.checked)
  {
    phone.disabled = false;
    phone.style.backgroundColor = "#FFFFFF";
    phone_carrier.disabled = false;
    phone_carrier.style.backgroundColor = "#FFFFFF";
  } else {
    phone.disabled = true;
    phone.style.backgroundColor = "#EEEEEE";
    phone_carrier.disabled = true;
    phone_carrier.style.backgroundColor = "#EEEEEE";
  }
}
</script>

<br>
<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" <?php echo ($tab == 'account') ? 'class="active"' : ''; ?>><a href="#account" aria-controls="account" role="tab" data-toggle="tab">Account</a></li>
    <li role="presentation" <?php echo ($tab == 'tags') ? 'class="active"' : ''; ?>><a href="#tags" aria-controls="tags" role="tab" data-toggle="tab">Tags</a></li>
    <li role="presentation" <?php echo ($tab == 'image') ? 'class="active"' : ''; ?>><a href="#image" aria-controls="image" role="tab" data-toggle="tab">Image</a></li>
    <li role="presentation"><a href="#password" aria-controls="password" role="tab" data-toggle="tab">Password</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane<?php echo ($tab == 'account') ? ' active' : ''; ?>" id="account">
    	<h3>Account Settings</h3>
    	<form action="<?php echo base_url(); ?>main/update_account" method="post">
    		<fieldset class="form_inner">
    		    <legend>Update Your Account Information</legend>
    		    <div class="form-group">
    		    	<label style="text-align=:left" for="first_name">First Name</label>
    		    	<input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user_info->first_name; ?>" />
    		    </div>
    		    <div class="form-group">
    		    	<label style="text-align=:left" for="last_name">Last Name</label>
    		    	<input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user_info->last_name; ?>" />
    		    </div>
    		    <div class="form-group">
    					<label style="text-align=:left" for="username">Username</label>
    					<input type="text" class="form-control" id="username" name="username" value="<?php echo $user_info->username; ?>" />
    		    </div>
    		    <div class="form-group">
    		    	<label style="text-align=:left" for="email">Email</label>
    		    	<input type="text" class="form-control" id="email" name="email" value="<?php echo $user_info->email; ?>" />
    		    </div>
            <div class="form-group">
              <label style="text-align=:left" for="notification">Notification Method</label>
              <p>
                <input type="radio" id="notification" name="notification" value="email" <?php if($user_info->notification == "email"){echo "checked";}?>>
                <?php echo " Email";?></p>
              <p>
                <input type="radio" id="notification" name="notification" value="phone" <?php if ($user_info->notification == "phone"){echo "checked";}?>>
                <?php echo " Phone <i>(Standard text message charges may apply)</i>";?></p>
              <p>
                <input type="radio" id="notification" name="notification" value="none" <?php if ($user_info->notification == "none"){echo "checked";}?>>
                <?php echo " No Notifications <i>(You could miss important alerts)</i>";?></p>
            </div>
            <div class="form-group">
    		    	<label style="text-align=:left" for="phone">Phone</label>
    		    	<input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user_info->phone; ?>" />
    		    </div>
            <div class="form-group">
    		    	<label style="text-align=:left" for="phone_carrier">Phone Carrier/Service Provider</label>
              <?php $carrier = $user_info->phone_carrier; ?>
              <select class="form-control" id="phone_carrier" name="phone_carrier">
                <option value="ATT" <?php echo ($carrier == 'ATT') ? 'selected' : ''; ?>>AT&T</option>
                <option value="Boost" <?php echo ($carrier == 'Boost') ? 'selected' : ''; ?>>Boost</option>
                <option value="Cingular" <?php echo ($carrier == 'Cingular') ? 'selected' : ''; ?>>Cingular</option>
                <option value="Nextel" <?php echo ($carrier == 'Nextel') ? 'selected' : ''; ?>>Nextel</option>
                <option value="Sprint" <?php echo ($carrier == 'Sprint') ? 'selected' : ''; ?>>Sprint</option>
                <option value="T-Mobile" <?php echo ($carrier == 'T-Mobile') ? 'selected' : ''; ?>>T-Mobile</option>
                <option value="US Cellular" <?php echo ($carrier == 'US Cellular') ? 'selected' : ''; ?>>US Cellular</option>
                <option value="Verizon" <?php echo ($carrier == 'Verizon') ? 'selected' : ''; ?>>Verizon</option>
                <option value="Virgin Mobile" <?php echo ($carrier == 'Virgin Mobile') ? 'selected' : ''; ?>>Virgin Mobile</option>
              </select>
    		    </div>
    		    <div class="form-group">
    		    	<label style="text-align=:left" for="location">Location</label>
    		    	<input type="text" class="form-control" id="location" name="location" value="<?php echo $user_info->location; ?>" />
    		    </div>
    		    <div class="form-group">
    		    	<label for="description">Description</label>
    		    	<textarea class="form-control" rows="3" id="description" name="description"><?php echo $user_info->description; ?></textarea>
    		    </div>
    		    <div class="form-group">
    		    	<label style="text-align=:left" for="location">Group Label</label>
    		    	<input type="text" class="form-control" id="interest_group_name" name="interest_group_name" value="<?php echo $user_info->interest_group_name; ?>" />
    		    </div>
    		    <input type="hidden" name="id" value="<?php echo $user_info->id; ?>">
    		    <button type="submit" class="btn btn-default">Update Settings</button>
    		</fieldset>
    	</form>
    </div>
    <div role="tabpanel" class="tab-pane<?php echo ($tab == 'tags') ? ' active' : ''; ?>" id="tags">
    <h3>Interest and Skill Tags</h3>
    <p class="help-block">You may have a maximum of <?php echo $this->config->item('btf_max_interests'); ?> Interest and <?php echo $this->config->item('btf_max_skills'); ?> Skill tags. You can add tags below (if you have Skill or Interest tags remaining). To delete an existing tag, simply click on it below. You and other collaborators can view your Skills and Interests on your <a href="<?php echo base_url(); ?>main/user_profile/<?php echo $user_info->id; ?>">profile page</a>.</p>
    <div class="bs-callout bs-callout-warning">
    	<h4>Interests (<?php echo $interest_count; ?>)</h4>
    	<?php
    	foreach ($interests as $interest)
    	{
    		echo '<a href="'.base_url().'main/delete_tag/'.$interest['PK_Tag_Id'].'" class="btn btn-warning btn-sm">'.ucwords($interest['Tag_Text']).'</a> ';
    	}
    	if ($interest_count < $this->config->item('btf_max_interests'))
    	{
    		echo '<br><br>';
    		echo '<form action="'.base_url().'main/add_tag" method="post">';
    		echo '<input type="hidden" name="FK_User_Id" value="'.$user_info->id.'">';
    		echo '<input type="hidden" name="Tag_Type" value="Interest">';
    		echo '<div class="input-group">';
    		echo '<input id="I_Tag_Text" type="text" class="form-control" name="I_Tag_Text" placeholder="Interest..."/>';
    		echo '<span class="input-group-btn">';
    		echo '<button style="vertical-align:top" class="btn btn-default" type="submit">Add Interest</button>';
    		echo '</span>';
    		echo '</div>';
    		echo '</form>';
    	}
    	?>
    </div>
    <div class="bs-callout bs-callout-success">
    	<h4>Skills (<?php echo $skill_count; ?>)</h4>
    	<?php
    	foreach ($skills as $skill)
    	{
    		echo '<a href="'.base_url().'main/delete_tag/'.$skill['PK_Tag_Id'].'" class="btn btn-success btn-sm">'.ucwords($skill['Tag_Text']).'</a> ';
    	}
    	if ($skill_count < $this->config->item('btf_max_skills'))
    	{
    		echo '<br><br>';
    		echo '<form action="'.base_url().'main/add_tag" method="post">';
    		echo '<input type="hidden" name="FK_User_Id" value="'.$user_info->id.'">';
    		echo '<input type="hidden" name="Tag_Type" value="Skill">';
        echo '<div class="row">';
        echo '<div class="input-group">';
    		echo '<input id="S_Tag_Text" type="text" class="form-control" name="S_Tag_Text" placeholder="Skill..."/>';
    		echo '<span class="input-group-btn">';
    		echo '<button style="vertical-align:top" class="btn btn-default" type="submit">Add Skill</button>';
    		echo '</span>';
    		echo '</div>';
        echo '</div>';
    		echo '</form>';
    	}
    	?>
    </div>
    </div>
    <div role="tabpanel" class="tab-pane<?php echo ($tab == 'image') ? ' active' : ''; ?>" id="image">

    	<h3>Profile Image</h3>


         <img src="<?php echo base_url(); ?>user_mugs/<?php echo $user_info->mug; ?>">



             <form action="<?php echo base_url().'main/update_photo'; ?>" method="post" enctype="multipart/form-data">

                 <fieldset class="form_inner">
                     <legend>Manage Your Profile Image</legend>
                     <div class="form-group">
                       <label for="userfile">Image file</label>
                       <input type="file" id="userfile" name="userfile">
                       <p class="help-block">Profile images can be in jpg, png or gif format, with a maximum dimension of 480 pixels. A square picture will yield the best results. Profile pictures will be displayed as 150px by 150px.</p>
                     </div>
                     <button type="submit" class="btn btn-default">Upload Image</button>
                 </fieldset>
             </form>
  </div>
    <div role="tabpanel" class="tab-pane" id="password">
    	<h3>Your Password</h3>
    	<form action="<?php echo base_url(); ?>main/update_password" method="post">
    		<fieldset class="form_inner">
    			<legend>Change Your Password</legend>
    			<div class="form-group">
    				<label style="text-align=:left" for="password">Current Password</label>
    				<input type="password" class="form-control" id="password" name="password" value="" />
    			</div>
    			<div class="form-group">
    				<label style="text-align=:left" for="new_password">New Password</label>
    				<input type="password" class="form-control" id="new_password" name="new_password" value="" />
    			</div>
    			<div class="form-group">
    				<label style="text-align=:left" for="new_password_again">New Password (again)</label>
    				<input type="password" class="form-control" id="new_password_again" name="new_password_again" value="" />
    			</div>
    		</fieldset>
    		<button type="submit" class="btn btn-default">Change Password</button>
    	</form>
	</div>
</div> <!-- Tab content -->
</div> <!-- whole tab shebang -->
<script>
    $("#I_Tag_Text").autocomplete({
        source: function(req, add){
            var tagType = "Interest";
            var tagText = $("#I_Tag_Text").val();
            $.ajax({
                url: "http://63.247.137.231/~btffellows/index.php/main/tag_suggest/"+tagText+"/"+tagType,
                dataType: 'json',
                type: 'POST',
                data: req,
                success: function(data){
                    if(data.message != null){
                        add(data.message);
                    }
                }
            });
        },
        minLength: 2
    });
    $("#S_Tag_Text").autocomplete({
        source: function(req, add){
          var tagType = "Skill";
          var tagText = $("#S_Tag_Text").val();
            $.ajax({
                url: "http://63.247.137.231/~btffellows/index.php/main/tag_suggest/"+tagText+"/"+tagType,
                dataType: 'json',
                type: 'POST',
                data: req,
                success: function(data){
                    if(data.message != null){
                        add(data.message);
                    }
                }
            });
        },
        minLength: 2
    });
  </script>
</div>
