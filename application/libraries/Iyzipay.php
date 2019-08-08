<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iyzipay {

	//all string start
	private $apiKey = '';
	private $secretKey = '';
	private $baseUrl = '';
	private $conversationId = '';
	private $price = '';
	private $basketId = '';
	private $cardHolderName = '';
	private $cardNumber = '';
	private $expireMonth = '';
	private $expireYear = '';
	private $cvc = '';
	private $user_id = '';
	private $user_name = '';
	private $user_surname = '';
	private $user_phone = '';
	private $user_email = '';
	private $identityNumber = '';
	private $lastLoginDate = '';
	private $registrationDate = '';
	private $address = '';
	private $user_ip = '';
	private $city = '';
	private $itemName = '';
	private $zipCode = '';
	private $itemCategory = '';
	//all string end

	public function __construct($iyziArray = array())
	{
		require_once(FCPATH.'application/libraries/iyzipay-php/IyzipayBootstrap.php');
		IyzipayBootstrap::init();
		$this->initialize($iyziArray);
	}

	public function initialize($config = array())
	{
		if (count($config) > 0)
		{
			foreach ($config as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}

	private function Options()
	{
		$options = new \Iyzipay\Options();
		$options->setApiKey($this->apiKey);
		$options->setSecretKey($this->secretKey);
		$options->setBaseUrl($this->baseUrl);
		return $options;
	}

	private function CreatePaymentRequest()
	{
		$request = new \Iyzipay\Request\CreatePaymentRequest();
		$request->setLocale(\Iyzipay\Model\Locale::TR);
		$request->setConversationId($this->conversationId);
		$request->setPrice($this->price);
		$request->setPaidPrice($this->price);
		$request->setCurrency(\Iyzipay\Model\Currency::TL);
		$request->setInstallment(1);
		$request->setBasketId($this->basketId);
		$request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
		$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
		return $request;
	}

	private function PaymentCard($request)
	{
		$paymentCard = new \Iyzipay\Model\PaymentCard();
		$paymentCard->setCardHolderName($this->cardHolderName);
		$paymentCard->setCardNumber($this->cardNumber);
		$paymentCard->setExpireMonth($this->expireMonth);
		$paymentCard->setExpireYear($this->expireYear);
		$paymentCard->setCvc($this->cvc);
		$paymentCard->setRegisterCard(1);
		$request->setPaymentCard($paymentCard);
		return $request;
	}

	private function Buyer($request)
	{
		$buyer = new \Iyzipay\Model\Buyer();
		$buyer->setId($this->user_id);
		$buyer->setName($this->user_name);
		$buyer->setSurname($this->user_surname);
		$buyer->setGsmNumber($this->user_phone);
		$buyer->setEmail($this->user_email);
		$buyer->setIdentityNumber($this->identityNumber);
		$buyer->setLastLoginDate($this->lastLoginDate);
		$buyer->setRegistrationDate($this->registrationDate);
		$buyer->setRegistrationAddress($this->address);
		$buyer->setIp($this->user_ip);
		$buyer->setCity($this->city);
		$buyer->setCountry("Turkey");
		$buyer->setZipCode($this->zipCode);
		$request->setBuyer($buyer);
		return $request;
	}

	private function Address($request,$adresType='Shipping')
	{
		$Address = new \Iyzipay\Model\Address();
		$Address->setContactName($this->cardHolderName);
		$Address->setCity($this->city);
		$Address->setCountry("Turkey");
		$Address->setAddress($this->address);
		$Address->setZipCode($this->zipCode);
		if($adresType=='Shipping'){
			$request->setShippingAddress($Address);
		}elseif($adresType=='Billing'){
			$request->setBillingAddress($Address);
		}
		return $request;		
	}

	private function BasketItem($request)
	{
		$basketItems = array();
		$BasketItem = new \Iyzipay\Model\BasketItem();
		$BasketItem->setId($this->basketId);
		$BasketItem->setName($this->itemName);
		$BasketItem->setCategory1($this->itemCategory);
		$BasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
		$BasketItem->setPrice($this->price);
		$basketItems[] = $BasketItem;
		$request->setBasketItems($basketItems);
		return $request;		
	}

	private function get_comission_rate($payment)
	{
	    $sum = ($payment->getIyziCommissionRateAmount() + $payment->getIyziCommissionFee());
	    return $sum;
	}

	public function result()
	{
		$options = $this->Options();
		$request = $this->CreatePaymentRequest();
		$request = $this->PaymentCard($request);
		$request = $this->Buyer($request);
		$request = $this->Address($request);
		$request = $this->Address($request,'Billing');
		$request = $this->BasketItem($request);
		$payment = \Iyzipay\Model\Payment::create($request, $options);

	    if(strtolower($payment->getStatus())=='success' && $payment->getFraudStatus() == 1){
	        $fees		= $this->get_comission_rate($payment);
	        $array	= array(
	            'status'	=> strtolower($payment->getStatus()),
	            'rawdata'	=> $payment->getRawResult(),
	            'transid'	=> $payment->getPaymentId(),
	            'fee'		=> $fees,
	        );
	    }else{
	        $array 	= array(
	            'status'	=> 'failed',
	            'rawdata'	=> $payment->getRawResult(),
	        );
	    }

		return $array;
	}
}