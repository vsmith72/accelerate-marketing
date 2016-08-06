<?php defined( 'ABSPATH' ) or die();

	
	$items = $table->get_history();
	date_default_timezone_set("UTC");
	
	//print_r($items);
?>

<div class="wrap">
<h2>Push History</h2>
<table class="widefat fixed">
	<thead>
		<tr>
			<th>Date / Time</th>
			<th>By User</th>
			<th>Push ID</th>
			<?php if( is_multisite() ): ?>
				<th>Site Name</th>
				<th>Site ID</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
	<?php foreach( $items as $key => $item ): 
		$date = new DateTime($item->pushTime);
	
		if( is_multisite() ){
			$details = get_blog_details( $item->siteId );
			$siteName = is_object($details) ? $details->blogname : 'Unknown';
		}
		
	?>
		<tr>
			<td><?php echo $item->pushTime; ?> UTC (<?php echo human_time_diff( $date->getTimestamp() ); ?> ago)</td>
			<td><?php echo get_userdata($item->user)->user_login; ?></td>
			<td><?php echo $item->pushId; ?></td>
			<?php if( is_multisite() ): ?>
				<td><?php echo $siteName; ?></td>
				<td><?php echo $item->siteId; ?></td>
			<?php endif; ?>
		</tr>	
	<?php endforeach; ?>
	</tbody>
</table>
</div>