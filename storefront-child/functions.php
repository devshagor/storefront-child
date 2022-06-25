<?php 
	function storefront_child_enqueue_scripts() {
		wp_enqueue_style(
			'storefront-child-style',
			get_stylesheet_directory_uri() . '/assets/css/custom.css',			
		);
	}
	add_action( 'wp_enqueue_scripts', 'storefront_child_enqueue_scripts', 20 );

	add_action( 'woocommerce_cart_calculate_fees','wc_cart_quantity_discount', 10, 1 );
	function wc_cart_quantity_discount( $cart_object ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
			return;

		// define variable to set discount 
		$discount = 0;
		$cart_item_count = $cart_object->get_cart_contents_count();
		$cart_total_excl_tax = $cart_object->subtotal_ex_tax;

		// checking the conditional percentage
		if( $cart_item_count <= 2 ) {	
			$percent = 0;
		}
		elseif( $cart_item_count >= 3 ) {
			$percent = 10;
		}
		// more conditon if we need  
		// elseif( $cart_item_count > 10 && $cart_item_count <= 15 ) {
		// 	$percent = 15;
		// }
		// elseif( $cart_item_count > 15 && $cart_item_count <= 20 ) {
		// 	$percent = 20;
		// }
		// elseif( $cart_item_count > 20 && $cart_item_count <= 25 ) {
		// 	$percent = 25;
		// }
		// elseif( $cart_item_count > 25 ) {
		// 	$percent = 30;
		// }

		// discount calculation  
		$discount -= ($cart_total_excl_tax / 100) * $percent;

		// applying calculated discount taxable 
		if( $percent > 0 ) {
			$cart_object->add_fee( __( "Quantity discount $percent%", "storefront-child" ), $discount, true);
		}
	}

