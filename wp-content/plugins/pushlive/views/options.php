<?php
	defined( 'ABSPATH' ) or die();
?>

<div class="wrap">
	<div class="pushlive-website-link">
		<div class="rateme" title="Rate PushLive">
			<a href="https://wordpress.org/support/view/plugin-reviews/pushlive?rate=1" class="star" target="_blank">&#9733;</a>
			<a href="https://wordpress.org/support/view/plugin-reviews/pushlive?rate=2" class="star" target="_blank">&#9733;</a>
			<a href="https://wordpress.org/support/view/plugin-reviews/pushlive?rate=3" class="star" target="_blank">&#9733;</a>
			<a href="https://wordpress.org/support/view/plugin-reviews/pushlive?rate=4" class="star" target="_blank">&#9733;</a>
			<a href="https://wordpress.org/support/view/plugin-reviews/pushlive?rate=5" class="star" target="_blank">&#9733;</a>
		</div>
		<a href="<?php echo PUSHLIVE__PLUGINSITE; ?>" target="_blank">PushLive Help</a>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="8XN6UEGZEQNWQ">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Please donate to show your appreciation for PushLive">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>		
	</div>
	<h2 class="pl-title"><a href="admin.php?page=pushlive-admin-tool" title="Run PushLive Here">PushLive</a> Options</h2>
	<form method="POST" action="<?php echo is_network_admin() ? '../' : ''; ?>options.php" class="pushlive">
		<?php 
			submit_button();
			settings_fields( 'pushlive-admin-options' ); 
			do_settings_sections( 'pushlive-admin-options' );
			submit_button();
		?>
	</form>
</div>
<div class="pushlive-cursor"></div>
<script>
jQuery( document ).ready( function( $ ) {
	$('form.pushlive').submit( function( event ){
		$('.pushlive-cursor').show();
		$('body').on( "mousemove", function( e ) {
			$('.pushlive-cursor').offset({ 
				left: ( e.pageX - 32 )
				, top :  (e.pageY - 32 )
			})
		});
	});
});
</script>