## iyzipay (by iyzico) Integration for Codeigniter

### Documentation
[https://dev.iyzipay.com/tr][https://dev.iyzipay.com/tr]
### Dependence
[https://github.com/iyzico/iyzipay-php][https://github.com/iyzico/iyzipay-php]


### Usage
```php
		$iyziArray = [
			'apiKey'			=>	'your api key',
			'secretKey'			=>	'"your secret key',
			'baseUrl'			=>	'https://sandbox-api.iyzipay.com',
			'conversationId'	=>	'1',
			'price'				=>	'1',
			'basketId'			=>	'5151',
			'cardHolderName'	=>	'John Doe',
			'cardNumber'		=>	'5528790000000008',
			'expireMonth'		=>	'12',
			'expireYear'		=>	'2030',
			'cvc'				=>	'123',
			'user_id'			=>	'1',
			'user_name'			=>	'John',
			'user_surname'		=>	'Doe',
			'user_phone'		=>	'+905350000000',
			'user_email'		=>	'email@email.com',
			'user_ip'			=>	'10.0.0.1',
			'identityNumber'	=>	'74300864791',
			'lastLoginDate'		=>	'2015-10-05 12:43:35',
			'registrationDate'	=>	'2013-04-21 15:12:09',
			'address'			=>	'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1',
			'city'				=>	'İstanbul',
			'zipCode'			=>	'34000',
			'itemName'			=>	'Binocular',
			'itemCategory'		=>	'Accessories'
		];
		$this->load->library('Iyzipay',$iyziArray);
		$payment = $this->iyzipay->result();
		echo $payment['rawdata'];
```

#### Author
Burak Berktürk