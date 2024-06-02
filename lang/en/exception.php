<?php

return [

    'unauthorized' => 'Unauthorized',
    'not_found' => [
        'default' => 'Item not found',
        'user' => 'User not found',
        'author' => 'Author not found',
        'book' => 'Book not found',
        'loan' => 'Loan not found',
    ],
    'bad_request' => [
        'default' => 'Bad request',
        'invalid_id' => 'Invalid id',
    ],
    'internal_server_error' => 'Internal server error',

    'queue' => [
        'email_sending' => 'Email sending queue error',
    ],

    'external_request' => [
        'external_request_error' => 'External request with error',
        'invalid_uri' => 'Invalid uri',
        'invalid_method' => 'Invalid method',
    ],

    'loan_already_been_returned' => 'Loan has already been returned',

    'and_more_error' => '(and :amount more error)',
    'and_more_errors' => '(and :amount more errors)',

    'invalid_signature' => 'Invalid signature',
    'error_decoding_base64' => 'Error decoding base64',

];
