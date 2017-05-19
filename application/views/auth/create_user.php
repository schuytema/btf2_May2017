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

<div class="starter-template">
  <img src="<?php echo base_url(); ?>img/home_logo_2.png"/>
  <div style="max-width:400px; margin-left:auto; margin-right:auto; border:1px solid #888; padding:8px">
    <div align="left">
      <h2>Create Account</h2>
      <p>Please enter your information below.</p>
      <?php if(stripos($message, "Identity field must contain a unique value"))
        $message = "The username you entered already exists. Please choose a unique username.";
      ?>
      <div id="infoMessage" style="color:red"><?php echo $message;?></div>

      <?php echo form_open("auth/create_user");?>
        <div class="form-group">
          <label for="first_name"><?php echo lang('create_user_fname_label', 'first_name');?></label>
          <input type="text" class="form-control" id="first_name" name="first_name" autofocus>
        </div>
        <div class="form-group">
          <label for="last_name"><?php echo lang('create_user_lname_label', 'last_name');?></label>
          <input type="text" class="form-control" id="last_name" name="last_name">
        </div>
        <div class="form-group">
          <label for="identity"><?php echo lang('create_user_identity_label', 'identity');?></label>
          <input type="text" class="form-control" id="identity" name="identity">
        </div>
        <div class="form-group">
          <label for="email"><?php echo lang('create_user_email_label', 'email');?></label>
          <input type="text" class="form-control" id="email" name="email">
        </div>
        <div class="form-group">
          <label for="password"><?php echo lang('create_user_password_label', 'password');?></label>
          <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group">
          <label for="password_confirm>"><?php echo lang('create_user_password_confirm_label', 'password_confirm');?></label>
          <input type="password" class="form-control" id="password_confirm" name="password_confirm">
        </div>
        <div class="form-group">
          <label >
            <input type="checkbox" id="checkbox" name="checkbox" value="checkbox" onclick="disableText();" checked>
            Receive Text Notification
          </label>
          <p class="help-block"><i>Standard text message charges may apply</i></p>
        </div>
        <div class="form-group">
          <label for="phone">
            <?php echo "Phone Number ";?>
            <a class="glyphicon glyphicon-question-sign" style="color:blue">
              <span class="tooltiptext" style="width:200px!important;">
                Your phone number is required for notification purposes. If you do not wish to receive notifications via text messages you may uncheck the box above. You can always change your notification preference in your profile settings.
              </span>
            </a>
          </label>
          <input type="text" class="form-control" id="phone" name="phone" value="0" placeholder="">
        </div>
        <div class="form-group">
          <label for="phone_carrier">
            <?php echo "Phone Carrier/Service Provider ";?>
            <a class="glyphicon glyphicon-question-sign" style="color:blue">
              <span class="tooltiptext" style="width:200px!important;">
                Please select your service provider from the dropdown list below. your service provider is required for text notifications to work. If you change your provider in the future, make sure you change it in your profile settings.
              </span>
            </a>
          </label>
          <select class="form-control" id="phone_carrier" name="phone_carrier" value="">
            <option selected disabled></option>
            <option value="ATT">AT&T</option>
            <option value="Boost">Boost</option>
            <option value="Cingular">Cingular</option>
            <option value="Nextel">Nextel</option>
            <option value="Sprint">Sprint</option>
            <option value="T-Mobile">T-Mobile</option>
            <option value="US Cellular">US Cellular</option>
            <option value="Verizon">Verizon</option>
            <option value="Virgin Mobile">Virgin Mobile</option>
          </select>
        </div>
        <div class="form-group">
          <label for="confidential">Confidentiality</label>
          <div class="checkbox">
            <label>
              <input type="checkbox" value="yes" name="confidential" id="confidential">
              I understand the need for confidentiality.
            </label>
          </div>
          <p class="help-block">This version of the Breakthrough Foundry is under development. Please do not share information about this site with non-users of this beta version of the BTF.</p>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">
        <?php echo lang('create_user_submit_btn');?>
      </button>

    <?php echo form_close();?>
  </div>
</div>