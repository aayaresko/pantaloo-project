<?php

return [
    'connection' => 'http://' . env('BITCOIN_USERNAME') . ':' . env('BITCOIN_PASSWORD') . '@' . env('BITCOIN_HOST') . ':' . env('BITCOIN_PORT')
];