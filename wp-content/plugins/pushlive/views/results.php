<?php
	defined( 'ABSPATH' ) or die();
?>
	
	<div class="wrap pl-results">
		<h2>Push Results</h2>
		<table class="widefat">
			<tr>
				<?php foreach( $results as $type => $data ): ?>
				<td><h3><?php echo ucwords($type); ?></h3></td>
				<td>
					<table>
						<thead>
							<tr>
								<th>Task</th>
								<th>Result</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $data as $task => $result ): ?>
							<tr>
								<td><h4><?php echo ucwords($task); ?>:</h4></td>
								<td><h4 class="result <?php echo strtolower(strtok($result, " ")); ?>"><?php echo $result; ?></h4></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</td>
				<?php endforeach; ?>
			</tr>
		</table>
		<strong>Like PushLive? Please help us out by donating for all the hard work and effort we put into it for you - Thank you so much!</strong>
	</div>