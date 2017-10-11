<?php

	class EDD_CPS
	{
		function __construct() {
			$this->do_init();
		}

		public static function init()
		{
			static $instance = null;
			if ($instance === null) {
				$instance = new EDD_CPS();
			}
		}

		function do_init()
		{
			add_action( 'cmb2_init', array($this,'register_fields') );
			add_action( 'edd_complete_purchase', array($this,'generate_cps_license') );
		}

		/**
		 * Add a CPS License Generator ID field to each EDD Product
		 */
		function register_fields()
		{
			$prefix = '_eddcps_';

			$cmb = new_cmb2_box( array(
				'id'            => $prefix.'metabox',
				'title'         => __( 'CPS Licensing', 'cmb2' ),
				'object_types'  => array( 'download', ), // Post type
				'context'       => 'normal',
				'priority'      => 'high',
				'show_names'    => true, // Show field names on the left
			) );

			$cmb->add_field( array(
				'name'      => __( 'License Generator ID', 'edd-cps' ),
				'id'        => $prefix. 'licensegenid',
				'type'      => 'text',
			) );
		}


		/**
		 * Generates 1 or more licenses from EDD cart using Copy Protect Software API
		 * @param $payment_id
		 */
		function generate_cps_license($payment_id)
		{
			$payment_meta = edd_get_payment_meta( $payment_id );

			$email = $payment_meta->email;
			$name = $payment_meta->first_name.' '.$payment_meta->last_name;
			$transaction_id = $payment_meta->transaction_id;

			$cps_api = new copyprotectsoftwareapi();

			$cart_items = edd_get_payment_meta_cart_details( $payment_id );
			foreach($cart_items as $item)
			{
				$download_id = $item->id;
				$license_gen_id = get_post_meta( $download_id, '_eddcps_licensegenid', true );

				$cps_api->generate_license($license_gen_id,$name, $email, $transaction_id);
			}
		}

	}