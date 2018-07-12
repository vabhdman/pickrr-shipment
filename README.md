## Pickrr Shipment Extension for Magento

Pickrr Magento 2 module for automatic/manual creation of shipments through Pickrr.

###Installation Instructions:

1. Extract the zip file contents into the `<root_of_Magento2>/app/code/Pickrr/Shipment` (you will need to create `Pickrr/Shipment` folders) <br>
The contents should look like `<root_of_Magento2>/app/code/Pickrr/Shipment/registration.php`

2. Goto root folder of magento in terminal, and run:
 ```shell
 bin/magento module:enable Pickrr_Shipment
 bin/magento setup:upgrade
 
 ```
3. Verify in Magento Admin Panel whether the module is enabled. To check, go to Admin Panel >Stores>Configuration>Advanced>Advanced>Pickrr_shipment<br>
4. Goto Admin Panel >Stores>configuration>PickrrExtensions>PickrrShipment, and enable the automatic shipment option & enter the asked details.

In case of any problems/queries, contact info@pickrr.com

---
###Usage Instructions (only for manual calls, when automatic shipment mode is not enabled):

####Import helper class:

```php
//import helper class

Pickrr\Shipment\Helper\ExportShipment $helper;

```
####Create a simple Pickrr Shipment:


**Prototype of the function:**
```php
$this->helper->createOrderShipment($auth_token, $item_name, $from_name, $from_phone_number, $from_pincode, $from_address, $to_name, $to_phone_number, $to_pincode, $to_address, $invoice_amount, $order_id, $cod_amount);
```

It returns the tracking_id from Pickrr.
