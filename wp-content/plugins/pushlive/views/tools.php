<?php defined( 'ABSPATH' ) or die();

	global $wpdb;

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
	<h2 class="pl-title">PushLive - Push Your Site Live Here</h2>
	
<?php if( !get_site_option( 'pushlive_lock_pushing' ) || $wpdb->prefix == $wpdb->base_prefix ): ?>	
	<form method="POST" class="pushlive">
		<input type="hidden" name="pushlive-now" value="1" />
		<div>First time pushes can take a long time, pushes thereafter should be much quicker. Please be patient.</div>
		
		
		<div id="pushlive-confirm-push">
			<input type="button" name="pre-submit" id="pushlive-pre-button" class="button button-primary" value="Push Site Live Now">
		
		<div class="pushlive-confirm-answer">
		<strong>Are you absolutely 100% sure you want to make all the content in this site live?</strong>
		<?php
			submit_button('Yes', 'primary', 'submit', true, array( 'id'=>'pushlive-now-button', 'style'=>'display:none;') );
		?>
		</div>
		
		</div>

		
	</form>
<?php else: ?>

<h3>Pushing Has Been Temporarily Disabled - Please check back later</h3>
<h3>Reason: <?php echo get_site_option( 'pushlive_lock_reason' ); ?></h3>
<h3>You can contact your administrator to remind them to re-enable it</h3>


<?php endif; ?>
</div>
<div class="pushlive-cursor"></div>

<script>
	jQuery( document ).ready( function($) {

		$('.pushlive-confirm-answer').hide();

		$('#pushlive-confirm-push').hover( function(e){
			$('.pushlive-confirm-answer').show();
		});
		
		$('#pushlive-confirm-push').mouseleave( function(e){
			$('.pushlive-confirm-answer').hide();
		});

		$('#pushlive-now-button').click( function(e){

				$('#pushlive-pre-button').attr( 'value', 'Pushing Site Live - Please Wait...' );
				
				$('.pushlive-cursor').show();
				$('body').on( "mousemove", function (e) {
					$('.pushlive-cursor').offset({ 
						left: ( e.pageX - 32 )
						, top :  (e.pageY - 32 )
					})
				});

		});

		$('form.pushlive').submit( function(){
			$('body').find( "input[type=submit]" ).prop( 'disabled', true );
			$('*').click( function(e){
				e.stopPropagation();
				e.preventDefault();
			}).css( 'cursor', 'wait' );
		});
		$('#pushlive-now-button').show();
		

	});
</script>