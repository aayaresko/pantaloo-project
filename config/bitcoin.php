<?php

return [
    'connection' => 'http://'.getenv('BITCOIN_USERNAME').':'.getenv('BITCOIN_PASSWORD').'@'.getenv('BITCOIN_HOST').':'.getenv('BITCOIN_PORT'),
];
