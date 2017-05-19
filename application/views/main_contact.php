<div>
	<h1>Contact the Breakthrough Foundry</h1>
	<p>We'd love to hear from you! Use the form below to send a short message to the <b>Breakthrough Foundry</b> team.</p>
	<form action="<?php echo base_url(); ?>main/process_contact" method="post">
		<div class="form-group">
		  <label for="Email">Your Email</label>
		  <input type="text" class="form-control" id="Email" name="Email" value="">
		</div>
		<div class="form-group">
		  <label for="Subject">Subject</label>
		  <input type="text" class="form-control" id="Subject" name="Subject" value="">
		</div>
		<div class="form-group">
		  <label for="Message">Message</label>
		  <textarea class="form-control" rows="2" id="Message" name="Message"></textarea>
		</div>
		<button type="submit" class="btn btn-primary">Send Message</button>
	</form>
</div>
