<?php

namespace Pickrr\Shipment\Helper;

use Magento\Framework\Exception\LocalizedException;
class CreateShipment
extends \Magento\Framework\App\Helper\AbstractHelper
{
	public function createOrderShipment($auth_token, $item_name, $from_name, $from_phone_number, $from_pincode, $from_address, $to_name, $to_phone_number, $to_pincode, $to_address, $invoice_amount, $order_id, $cod_amount){
		try{
			$params = array(
	                  'auth_token' => $auth_token,
	                  'item_name' => $item_name,
	                  'from_name' => $from_name,
	                  'from_phone_number' => $from_phone_number,
	                  'from_pincode'=> $from_pincode,
	                  'from_address'=> $from_address,
	                  'to_name'=> $to_name,
	                  'to_phone_number' => $to_phone_number,
	                  'to_pincode' => $to_pincode,
	                  'to_address' => $to_address,
	                  'client_order_id' => $order_id,
	                  'invoice_value' => $invoice_amount,
	                  'cod_amount' => $cod_amount
	                );


	        $json_params = json_encode( $params );

	        $url = 'http://www.pickrr.com/api/place-order/';
	        //open connection
	        $ch = curl_init();

	        //set the url, number of POST vars, POST data
	        curl_setopt($ch,CURLOPT_URL, $url);
	        curl_setopt($ch,CURLOPT_POSTFIELDS, $json_params);
	        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	        //execute post
	        $result = curl_exec($ch);
	        $result = json_decode($result, true);

		        //close connection
	        curl_close($ch);

	        if(gettype($result)!="array")
	          throw new \Exception( print_r($result, true) . "Problem in connecting with Pickrr");

	        if($result['err']!="")
	          throw new \Exception($result['err']);

	        return $result['tracking_id'];
		}
		catch (\Exception $e) {
        	throw new LocalizedException(__('There was an error in creating the Pickrr shipment: %1.', $e->getMessage()));
    	}
	}
}