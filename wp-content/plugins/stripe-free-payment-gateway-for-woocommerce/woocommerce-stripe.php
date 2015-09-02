<?php
/*
Plugin Name: Stripe free payment gateway for WooCommerce
Plugin URI: http://solvercircle.com
Description: Add Stripe Payment Gateways for WooCommerce. 
Version: 1.0.0
Author: SolverCircle
Author URI: http://solvercircle.com
*/

function init_stripe(){
  function add_stripe_gateway_class( $methods ) {
	$methods[] = 'WC_Gateway_Stripe'; 
	return $methods;
  }
  add_filter( 'woocommerce_payment_gateways', 'add_stripe_gateway_class' );
  if(class_exists('WC_Payment_Gateway')){
	
	class WC_Gateway_Stripe extends WC_Payment_Gateway {
	  
	  public function __construct(){
		$this->id               = 'stripe';
		$this->icon             = apply_filters( 'woocommerce_stripe_icon', plugins_url( 'images/stripe.png' , __FILE__ ) );
		$this->has_fields       = true;
		$this->method_title     = 'Stripe';		
		$this->init_form_fields();
		$this->init_settings();
		$this->title              	  = $this->get_option( 'title' );
		$this->stripe_privatekey    = $this->get_option( 'stripe_privatekey' );
		$this->stripe_secretkey  = $this->get_option( 'stripe_secretkey' );
		$this->stripe_testmode       = $this->get_option( 'stripe_testmode' );
		$this->stripe_verifySSL       = $this->get_option( 'stripe_verifySSL' );
		
		define("STRIPE_PRIVATE_KEY", $this->stripe_privatekey); 
		define("STRIPE_SECRET_KEY", $this->stripe_secretkey);
		define("STRIPE_VERIFY_SSL", ($this->stripe_verifySSL=='yes'? false : true)); 
		define("STRIPE_SANDBOX", ($this->stripe_testmode=='yes'? true : false));
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	  }
	  
	  public function admin_options(){
		?>
		<h3><?php _e( 'Stripe', 'woocommerce' ); ?></h3>
		<p><?php _e( 'Stripe is one of the best Paymeny gateway', 'woocommerce' ); ?></p>
		<table class="form-table">
			  <?php $this->generate_settings_html(); ?>
		</table>
		<?php
	  }
	  
	  public function init_form_fields(){
		$this->form_fields = array(
			'enabled' => array(
			  'title' => __( 'Enable/Disable', 'woocommerce' ),
			  'type' => 'checkbox',
			  'label' => __( 'Enable Stripe', 'woocommerce' ),
			  'default' => 'yes'
			  ),
			'title' => array(
			  'title' => __( 'Title', 'woocommerce' ),
			  'type' => 'text',
			  'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
			  'default' => __( 'Stripe', 'woocommerce' ),
			  'desc_tip'      => true,
			  ),
			'stripe_privatekey' => array(
			  'title' => __( 'Private Key', 'woocommerce' ),
			  'type' => 'text',
			  'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
			  'default' => '',
			  'desc_tip'      => true,
			  'placeholder' => 'Your Private Key'
			  ),
			'stripe_secretkey' => array(
			  'title' => __( 'Secret Key', 'woocommerce' ),
			  'type' => 'text',
			  'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
			  'default' => '',
			  'desc_tip'      => true,
			  'placeholder' => 'Your Secret Key'
			  ),
			'stripe_verifySSL' => array(
			  'title'       => __( 'stripe verifySSL', 'woocommerce' ),
			  'type'        => 'checkbox',
			  'label'       => __( 'Enable verify SSL', 'woocommerce' ),
			  'default'     => 'no',
			  'description' => __( 'Set SSL verification turn on if you need.', 'woocommerce' )
			  ),
			'stripe_testmode' => array(
			  'title'       => __( 'stripe sandbox', 'woocommerce' ),
			  'type'        => 'checkbox',
			  'label'       => __( 'Enable stripe sandbox', 'woocommerce' ),
			  'default'     => 'no',
			  'description' => __( 'stripe sandbox can be used to test payments.', 'woocommerce' )
			)
			
		  );
	  }
	  
	  public function payment_fields(){			
		?>
        <table>
        	<tr>
            	<td><label class="" for="stripe_first_name"><?php echo __( 'First Name', 'woocommerce') ?></label></td>
                <td><input type="text" name="first_name_stripe" class="input-text" /></td>
            </tr>
            <tr>
            	<td><label class="" for="stripe_last_name"><?php echo __( 'Last Name', 'woocommerce') ?></label></td>
                <td><input type="text" name="last_name_stripe" class="input-text" /></td>
            </tr>
            <tr>
              <td><label class="" for="stripe_card_type"><?php echo __( 'Card Type', 'woocommerce') ?></label></td>
              <td>
                <select name="card_type_stripe">
                  <option value="Visa">Visa</option>
                  <option value="MasterCard">MasterCard</option>
                  <option value="JCB">JCB</option>
                  <option value="Discover">Discover</option>
                  <option value="Amex">Amex</option>
                  <option value="Diners Club">Diners Club / Carte Blanche</option>
                </select>  
              </td>
            </tr>
            <tr>
            	<td><label class="" for="stripe_cardno"><?php echo __( 'Card No.', 'woocommerce') ?></label></td>
                <td><input type="text" name="cardno_stripe" class="input-text" /></td>
            </tr>
            <tr>
            	<td><label class="" for="stripe_first_name"><?php echo __( 'Expiration date', 'woocommerce') ?>.</label></td>
                <td>
                	<select name="expmonth_stripe" id="expmonth">
                      <option value=""><?php _e( 'Month', 'woocommerce' ) ?></option>
                      <option value='01'>01</option>
                      <option value='02'>02</option>
                      <option value='03'>03</option>
                      <option value='04'>04</option>
                      <option value='05'>05</option>
                      <option value='06'>06</option>
                      <option value='07'>07</option>
                      <option value='08'>08</option>
                      <option value='09'>09</option>
                      <option value='10'>10</option>
                      <option value='11'>11</option>
                      <option value='12'>12</option>  
                    </select>
                    <select name="expyear_stripe" id="expyear">
                      <option value=""><?php _e( 'Year', 'woocommerce' ) ?></option><?php
                      $years = array();
                      for ( $i = date( 'y' ); $i <= date( 'y' ) + 15; $i ++ ) {
                        printf( '<option value="20%u">20%u</option>', $i, $i );
                      } ?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td><label class="" for="cardcvv"><?php echo __( 'Card CVV', 'woocommerce') ?></label></td>
                <td><input type="text" name="cardcvv_stripe" class="input-text" /></td>
            </tr>
        </table>
		<?php
	  }
	  
	  public function process_payment( $order_id ){
		global $woocommerce;
		$wc_order = new WC_Order( $order_id );
		$grand_total = $wc_order->order_total;
		$amount = (int)$grand_total;
		include(plugin_dir_path( __FILE__ )."lib/Stripe.php");
		Stripe::setApiKey($this->stripe_secretkey); 
		Stripe::setApiVersion("2014-06-17");
		
		$token_id = Stripe_Token::create(array(
			 "card" => array( 
			 			"number" => $_POST['cardno_stripe'], 
						"exp_month" => $_POST['expmonth_stripe'], 
						"exp_year" => $_POST['expyear_stripe'], 
						"cvc" => $_POST['cardcvv_stripe'] ) 
			 ));
		$charge = Stripe_Charge::create(array( 
			"amount" => $amount, 
			"currency" => "USD", 
			"card" => $token_id->id, 
			"metadata" => array("order_id" => $order_id) 
		));
			if($token_id->id !=''){
			  if ($charge->paid == true) {
			  	$wc_order->add_order_note( __( ' Stripe payment completed. ' , 'woocommerce' ) );
				$wc_order->payment_complete();
				return array (
				  'result'   => 'success',
				  'redirect' => $this->get_return_url( $wc_order ),
				);
			  }
			  else{
				$wc_order->add_order_note( __( 'Stripe payment failed. Payment declined.', 'woocommerce' ) );
				$woocommerce->add_error( __( 'Sorry, the transaction was declined.', 'woocommerce' ) );
			  }
			}
			else{
				 $wc_order->add_order_note( __( 'Stripe payment failed. Payment declined. Please Check your Admin settings', 'woocommerce' ) );
				 $woocommerce->add_error( __( 'Sorry, the transaction was declined. Please Check your Admin settings', 'woocommerce' ) );
			 }
	   }
	}
  }
}

add_action( 'plugins_loaded', 'init_stripe' );
