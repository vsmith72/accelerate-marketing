<?php defined( 'ABSPATH' ) or die();
	
	global $wpdb;
	$pfx = $wpdb->prefix;
	
if ( current_user_can( 'manage_options' ) ):

	$table = new Pushlive_Exclude_Table();
	$items = $table->get_tables();
	$included = get_option( 'pushlive_exclude_tables' );
?>
<div><strong>FIRST PUSH ONLY:</strong> Check Everything except <strong><?php echo $pfx; ?>users & <?php echo $pfx; ?>usermeta</strong> if they are shown (strong suggestion)</div>
<div>Complete first PushLive push - Then come back and follow the rest of the suggestions below.</div>
<hr>
<div title="Most should be checked, they should be checked if they are not updated/used by the live site">
<strong>AFTER FIRST PUSH:</strong> Check every table that <strong>WILL REPLACE THE CURRENT TABLE</strong> in the live site.
</div>
<div>If the following are visible in this list it is strongly suggested you UNCHECK...</div>
<strong>
<div>&emsp;<?php echo $pfx; ?>commentmeta, <?php echo $pfx; ?>comments, <?php echo $pfx; ?>pushlive, <?php echo $pfx; ?>usermeta, <?php echo $pfx; ?>users</div>
</strong>
<div>and any other table updated by the live site.</div>
<div>If you have good reason and deeper understanding to do otherwise - you may check/uncheck whatever you wish.</div>
<div><a href="https://codex.wordpress.org/Database_Description#Table_Overview" target="_blank">Learn More About WordPress Tables Here</a></div>


	<?php if( !empty( $items ) ):?>
		<table class="table adminlist pushlive">
			<col width="0">
			<col width="75%">
			<thead>
				<tr>
					<th><input type="checkbox" id="pet_toggle" title="toggle all" />Include Selected</th>
					<th>Table Name</th>
				</tr>
			</thead>
			<tbody>
						
				<?php foreach( $items as $count => $item ): 
					$checked = in_array( $item, $included ) ? ' checked' : '';
				?>
					<tr class="row<?php echo $count%2; ?>">
						<td><input type="checkbox" name="pushlive_exclude_tables[]" value="<?php echo $item; ?>"<?php echo $checked; ?>></td>
						<td><?php echo $item; ?></td>
					</tr>
				<?php endforeach; ?>
			
			</tbody>
		</table>
		<script>
		//toggles the checkboxes state
		jQuery(document).ready(function($) {
			var pets = $("input[name='pushlive_exclude_tables[]']");
			var pets_chk = $("input[name='pushlive_exclude_tables[]']:checked");

			if(pets_chk.length > 0 ){
				$('#pet_toggle').prop('checked', true);
			}

			pets_chk.addClass('orig-selected'); 

		    $('#pet_toggle').click(function(){
		    	var pets_chk = $("input[name='pushlive_exclude_tables[]']:checked");
				if(pets.length > pets_chk.length){
					$(this).add(pets).prop('checked', true);	
				}else{
					pets.removeProp('checked');
				}
		    });

		    //Replaces http:// https:// and other /... where needed
		    no_http = 'input[name=pushlive_force_login], input[name=pushlive_stage_base], input[name=pushlive_live_base]';
		    $(no_http).blur(function(){
				$(this).val( $(this).val().replace( /http[s]*\:\/\//gi,'' ) );
		    });

		    
		});
		

		</script>
	<?php else: ?>
		<hr>There were no tables found in your database<hr>
	<?php endif; ?>	

<?php else:?>
<strong>You must have higher rights to view/edit the included & excluded tables section</strong>
<hr>
These options are usually set by an administrator who is knowlageable in the WordPress database structure.


<?php endif;?>