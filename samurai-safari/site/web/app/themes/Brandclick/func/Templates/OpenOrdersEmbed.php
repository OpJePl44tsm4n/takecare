<?php
@header( 'X-Frame-Options: ALLOWALL' );

$allowed_ips = get_option('site_redirect_allowed_ips');
$allowed_ips_array = $allowed_ips ? explode(",",$allowed_ips) : []; 

if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips_array)) wp_die('This page is private.');
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php _e('Processing Orders'); ?></title>

	<?php wp_head(); ?>
	<style>
		@import url("https://fonts.googleapis.com/css?family=Roboto:300,400,500,700");
		body { background:white; color:black; width: 100%; margin: 0 auto; font-family: "Roboto", "Helvetica Neue", Arial, sans-serif;  font-size: 0.875rem;  }
		table { border: 1px solid #f3f3f3; border-bottom: 1px solid #000; width: 100%; }
		table thead th { text-transform: capitalize; }
		table td, table th { padding: 10px 5px; }
		table tbody td { color:#909090; border: 1px solid #f3f3f3;}
		table tbody td b { font-size: 1rem; float: left;}
		table tbody td small { float: left; }
		table tbody tr { transition: opacity 1s ease; opacity: 1; }
		table tbody tr.completed { opacity: .1;}
		table tbody tr:nth-child(odd) td { background: #f9f9f9; }
		table tbody tr:hover:nth-child(odd) td { background: #e5e3e3; }
		table tbody tr:nth-child(odd) td:first-child { background: #f3f3f3; }
		table tbody tr:hover:nth-child(odd) td:first-child { background: #e5e3e3; }
		table tbody tr:hover { background-color: #e5e3e3;}
		button {  position: relative; display: inline-block;   font-weight: normal;   text-align: center;   white-space: nowrap;   vertical-align: middle;   user-select: none;   border: 1px solid transparent;   padding: 0.65rem 0.75rem;   font-size: 0.875rem;   line-height: 1.25;   border-radius: 3px;   transition: all 0.15s ease-in-out;   color: #fff;   background-color: #0866C6;   border-color: #0866C6;   cursor: pointer; }
		button:hover { color: #fff;   background-color: #0753a1;   border-color: #064d95; }
		button:disabled { background: rgba(8, 102, 198, 0.3); border-color:rgba(8, 102, 198, 0.2); cursor: default; }
		#message_bar {display:none; position: fixed;top:0;right:0;background: rgba(6,124,35,0.32);padding: 1em;}
		#message_bar.error { background: rgba(124,6,6,0.32); color: red;}
		button.loading:before,
		button.loading:after { right: 0;top: 0; content: ''; position: absolute; border: 4px solid #0866C6; opacity: 1;border-radius: 50%;animation: lds-ripple 2s cubic-bezier(0, 0.2, 0.8, 1) infinite;}
		button.loading:before { animation-delay: -1s;}
		@keyframes lds-ripple {
		  0% {top: 50%;right: 50%;width: 0;height: 0;opacity: 1;}
		  100% {top: calc(50% - 28px);right: calc(50% - 28px);width: 58px;height: 58px;opacity: 0;}
		}

	</style>
</head>
<body>

	<script>
		(function($){
    		jQuery( document ).ready( function () {

    			$('button').on('click', function(e){
    				e.preventDefault();
    				var btn = $(this);
    				btn.prop("disabled", true);;

    				type = btn.attr('name');
    				val = btn.attr('value');
    				btn.attr('class', 'loading');

		           	$.ajax({ 
				        data: {action: 'update_order', type:type, value:val},
				        type: 'post',
				        url: '<?php  echo admin_url('admin-ajax.php'); ?>',
				        success: function(data) {
				        	btn.attr('class', '');
				            order = JSON.parse(data); //should print out the name since you sent it along
				     		if( order.type == 'generate_label' && order.status == 'File uploaded' ) {
				     			$('[name="complete_order"][value="'+val+'"]').prop("disabled", false);
				     		} else if( order.type == 'complete_order' && order.status == 'completed' ){
				     			$('tr#'+val).addClass("completed").fadeOut(1000);
				     		} else {
				     			btn.prop("disabled", false);
				        	}

				        	$('#message_bar').html('Order ' + val + ', ' + order.status).fadeIn().fadeOut(3000); 

				        	if(order.debug !== false) {
				        		$('#message_bar').addClass('error');
				        	}
				        	
				        }
				  	});   
		            
		        });
		    });
    	})(jQuery);
	</script>

	<section>
		<?php 
		global $woocommerce;
		
		// Get orders on hold.
		$args = array(
		    'status' => 'processing',
		    'orderby' => 'date',
		    'order'	=> 'ASC' 
		);
		$orders = wc_get_orders( $args ); ?>
		
		<div id="message_bar"></div>

		<form id="update_order" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">

			<table cellspacing="0" cellpadding="2">
				<thead>
					<tr>
						<th scope="col" style="text-align:left;"><?php _e('Order ID', WhiteLabelTheme::THEME_SLUG); ?></th>
						<th scope="col" style="text-align:left;"><?php _e('Customer', WhiteLabelTheme::THEME_SLUG); ?></th>
						<th scope="col" style="text-align:left;"><?php _e('Label', WhiteLabelTheme::THEME_SLUG); ?></th>
						<th scope="col" style="text-align:left;"><?php _e('Action', WhiteLabelTheme::THEME_SLUG); ?></th>
					</tr>
				</thead>
		
				<tbody>
					<?php foreach ( $orders as $order ): 
						$order_data = $order->get_data();
						$billing_first_name = $order_data['billing']['first_name'][0];
	    				$billing_last_name = $order_data['billing']['last_name'];
	    				$file_status = get_post_meta($order->get_id(), 'bc_shipping_status', true);
	    				$complete_order = ($file_status == 'succes') ? '' : 'disabled="disabled"';
	    				$complete_label = ($file_status == 'succes') ? 'disabled="disabled"' : '';
						?>
						<tr id="<?php echo $order->get_id(); ?>">
							<td><?php echo sprintf('<b>#%s</b> </br><small>(%s)</small>', 
								$order->get_id(), 
								date('d/m/Y', strtotime( $order->get_date_created() ) )
							); ?></td>
							<td><?php echo $billing_first_name . ' ' . $billing_last_name; ?></td>
							<td>
							<input type="hidden" name="action" value="update_order">
							<button name="generate_label"  <?php echo $complete_label; ?> value="<?php echo $order->get_id(); ?>" type="submit" /><?php _e('Generate label', WhiteLabelTheme::THEME_SLUG); ?></button></td>
							<td><button name="complete_order" <?php echo $complete_order; ?> value="<?php echo $order->get_id(); ?>" type="submit" /><?php _e('Complete order', WhiteLabelTheme::THEME_SLUG); ?></button></td>	
						</tr>

					<?php endforeach; ?>
				</tbody>
			</table>
		</form>
	</section>
	
</body>
</html>