<div class="starter-template">
  <img src="<?php echo base_url(); ?>img/home_logo_2.png"/>
  </br>
  <div style="max-width:400px; margin-left:auto; margin-right:auto; border:1px solid #888; border-radius: 6%; padding:8px">
    <div align="left">
			<h2><?php echo lang('reset_password_heading');?></h2>

			<div id="infoMessage" style="color:red"><?php echo $message;?></div>

			<?php echo form_open('auth/reset_password/' . $code);?>

				<div class="form-group">
					<label for="new"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
					<input type="password" class="form-control" id="new" name="new">
					<!--<?php echo form_input($new_password);?>-->
				</div>

				<div class="form-group">
					<label for="new_confirm"><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?></label>
					<input type="password" class="form-control" id="new_confirm" name="new_confirm">
					<!--<?php echo form_input($new_password_confirm);?>-->
				</div>

				<?php echo form_input($user_id);?>
				<?php echo form_hidden($csrf); ?>

				<button type="submit" class="btn btn-primary">
	        <?php echo lang('reset_password_submit_btn');?>
	      </button>
			<?php echo form_close();?>
		</div>
	</div>
</div>
