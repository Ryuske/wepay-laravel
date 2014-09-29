## Wepay Wrapper For Laravel 4
This wrapper allows you to use the WePay API within Laravel using the standard Facade structure. If you would like more information on wepay, please checkout https://www.wepay.com/

## Installation
First you need edit your `composer.json` file with the following:

```js
"require-dev": {
    "ryuske/wepay-laravel": "dev-master"
}
```

And then run `composer install`.

Once that has finished, you need to edit 2 sections of your `app/config/app.php` configuration file.
The first section is the providers array, like so:

```php
'providers' => array(
    // ...

    'Ryuske\WepayLaravel\WepayLaravelServiceProvider'
)
```

The 2nd section is within the aliases array, like this:
```php
'aliases' => array(
    // ...

    'WepayWrapper'    => 'Ryuske\WepayLaravel\Facades\WepayLaravel'
)
```

You just finished the installation of the Wrapper! Whoo! But... Now we have to configure it.

## Configuration
First, run the command `php artisan config:publish ryuske/wepay-laravel`

Once you've run that command, open up the file `app/config/packages/ryuske/wepay-laravel/config.php`

Now, primarily what you need to change in this file are the production values for `client_id`, `client_secret`, `access_token` and `account_id`.

If you don't have a development environment setup, then set `useStaging = false` otherwise, file in the values for the staging array as well.

After those configuration values are set, you're ready to go.

## Code Example
```php
// This is the route where you would POST a form to
Route::post('/checkout', function() {
    $reference_id = 89678578; // This is some randomly generate ID for your records
    
    // On most API calls, the first parameter needs to be the access token of the person relieving money, the 2nd is the endpoint as per the WePay API docs and lastly the parameters for the API call as per the docs. 
    $response = WepayWrapper::request($user->wepay_token, '/credit_card/create', [
        'client_id' 		=> WepayWrapper::get('client_id'),
        'cc_number' 		=> Input::get('cc_number'),
        'cvv' 				=> Input::get('cvv'),
        'expiration_month' 	=> Input::get('expiration_month'),
        'expiration_year' 	=> Input::get('expiration_year'),
        'user_name' 		=> Input::get('name'),
        'email' 			=> Input::get('email'),
        'address' 			=> [
            'zip' 		=> Input::get('zip'),
            'country' 	=> Input::get('country')
        ]
    ]);
    
    WepayWrapper::request($user->wepay_token, '/credit_card/authorize', [
        'client_id' 		=> WepayWrapper::get('client_id'),
        'client_secret'		=> WepayWrapper::get('client_secret'),
        'credit_card_id'	=> $response->credit_card_id
    ]);
            
    WepayWrapper::request($user->wepay_token, '/checkout/create', [
        'account_id' 			=> $user->wepay_id, // This is the account id for whoever is recieving money
        'amount' 				=> Input::get('amount'),
        'currency' 				=> 'USD',
        'short_description' 	=> '<sku here>',
        'type'					=> 'GOODS',
        'long_description'		=> 'Purchase for <item here>, <sku here>',
        'reference_id'			=> $reference_id,
        'fallback_uri' 			=> action('CheckoutController@error'),
        'redirect_uri' 			=> action('ProductsController@show', ['id' => '<sku number here>']),
        'funding_sources' 		=> 'cc',
        'payment_method_id' 	=> $response->credit_card_id,
        'payment_method_type' 	=> 'credit_card'
    ]);
    
    $response = WepayWrapper::request($user->wepay_token, '/checkout/find', [
        'account_id' 			=> $user->wepay_id, // Again, this is the account id for whoever recieved the money
        'reference_id'			=> $reference_id,
    ]);
    
    // Here you would use the response to store values into the database or however you want to keep track of money that has been processed.			
    return $response;
});
```