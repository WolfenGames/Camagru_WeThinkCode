<?php
	session_start();
?>
<div>
	<video id="video">Video is loading...</video>
</div>
<div class="bar">
	<div style="margin: auto;">
		<input type="button" value="Take the Shot!!" id="snap">
	</div>
</div>
<canvas name="image" id="canvas">Canvas Still Loading</canvas>
<div id="options">
	<?php 
		if (isset($_SESSION['Username']))
		{ ?>
			<input type="button" class="button" name="Submit" onclick="sendData()" id="button" value="Submit Photo">
		<?php }
		else
		{
		?>
			<input class='btn' type='button' style='width: 100%;' value='Please login' disabled>
		<?php
		} 
		?>
</div>
<input type="button" value="Cancel" id="delete_snap">
<script src="js/camera.js"></script>