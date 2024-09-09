<?php
if (!defined('APP_ENVIROMENT')) {
    define('APP_ENVIROMENT', 'live'); // or 'sandbox'
}

if (!defined('API_URL')) {
    define('API_URL', APP_ENVIROMENT == 'sandbox' ? 'https://cybqa.pesapal.com/pesapalv3/api/' : 'https://pay.pesapal.com/v3/api/');
}

if (!defined('CONSUMER_KEY')) {
    define('CONSUMER_KEY', 'htMsEFfIVHfhqBL9O0ykz8wuedfFyg1s');
}

if (!defined('CONSUMER_SECRET')) {
    define('CONSUMER_SECRET', 'DcwkVNIiyijNWn1fdL/pa4K6khc=');
}
?>
