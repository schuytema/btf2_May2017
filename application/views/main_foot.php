
      
      <p class="footer"><a href="<?php echo base_url(); ?>main/about">About</a> | <a href="<?php echo base_url(); ?>main/help">Help</a> | <a href="<?php echo base_url(); ?>main/privacy">Privacy</a> | <a href="<?php echo base_url(); ?>main/contact">Contact</a><br>
      <em>Contents copyright &copy; 2016, The Breakthrough Foundry, LLC.</em>
      </p>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>js/moment.js"></script>
	<script src="<?php echo base_url(); ?>js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
	        $(function () {
	            $('#datetimepicker1').datetimepicker({
	            	format: 'YYYY-MM-DD'
	            });
	        });
	    });
	</script>
  </body>
</html>