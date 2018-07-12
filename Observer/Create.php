<?php
namespace Pickrr\Shipment\Observer;

use Magento\Framework\Event\Observer;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Event\ObserverInterface;
use \Pickrr\Shipment\Helper\CreateShipment;
class Create implements \Magento\Framework\Event\ObserverInterface
{

  private $orderFactory;

  public function __construct(
    \Magento\Sales\Model\OrderFactory $orderFactory,
    ScopeConfigInterface $scopeConfig,
    CreateShipment $helper) {
        $this->orderFactory   = $orderFactory;
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
  }

  public function execute(\Magento\Framework\Event\Observer $observer)
  {
  	try{
      if ("0" == $this->scopeConfig->getValue('pickrr_magento2/general/automatic_shipment_enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE))
          return NULL;
  		$lastOrderId = $observer->getEvent()->getData('order_ids');
  		$order = $this->orderFactory->create()->load($lastOrderId[0]);
  		$shipping_address = $order->getShippingAddress(); 

  	
	    $payment = $order->getPayment();
      $method = $payment->getMethod();
	    if(strpos($method, "cashondelivery") !== false)
	        $cod_amount = $order->getGrandTotal();
	    else
	        $cod_amount = 0.0;
	    $invoice_amount = $order->getGrandTotal();


	    $auth_token = $this->scopeConfig->getValue('pickrr_magento2/general/auth_token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

      $from_name = $this->scopeConfig->getValue('pickrr_magento2/shipment_details/from_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
      $from_phone_number = $this->scopeConfig->getValue('pickrr_magento2/shipment_details/from_phone_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
      $from_pincode = $this->scopeConfig->getValue('pickrr_magento2/shipment_details/from_pincode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
      $from_address = $this->scopeConfig->getValue('pickrr_magento2/shipment_details/from_address', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

	    
	    $shipping_address = $order->getShippingAddress();            
	    $to_name = $shipping_address->getName();
	    $to_phone_number = $shipping_address->getTelephone();
	    $to_pincode = $shipping_address->getPostcode();
	    $to_address = implode(', ', $shipping_address->getStreet()) . ", " . $shipping_address->getCity() . ", " . $shipping_address->getRegion();
	    $order_id = $order->getIncrementId();
	    $itemCount = $order->getTotalItemCount();
	    $item_name = "NULL";

    	if($itemCount==1) $item_name = $order->getItemsCollection()->getFirstItem()->getName();
    	else $item_name = 'Multiple Items';

      $this->helper->createOrderShipment($auth_token, $item_name, $from_name, $from_phone_number, $from_pincode, $from_address, $to_name, $to_phone_number, $to_pincode, $to_address, $invoice_amount, $order_id, $cod_amount);

	    

    }
    catch (\Exception $e) {
        throw new LocalizedException(__('There was an error in creating the Pickrr shipment: %1.', $e->getMessage()));
    }
  }
}