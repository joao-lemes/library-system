<?php

return [

    'unauthorized' => 'Não autorizado',
    'not_found' => [
        'default' => 'Item não encontrado',
        'user' => 'Usuário não encontrado',
        'author' => 'Autor não encontrado',
        'book' => 'Livro não encontrado',
        'loan' => 'Empréstimo não encontrado',
    ],
    'bad_request' => [
        'default' => 'Requisição ruim',
        'invalid_id' => 'Id inválido',
    ],
    'internal_server_error' => 'Erro do servidor interno',

    'queue' => [
        'email_sending' => 'Erro na fila de envio de email',
    ],
    
    'external_request' => [
        'error' => 'Requisição externa com erro',
        'invalid_uri' => 'Uri inválida',
        'invalid_method' => 'Método inválido',
    ],
    
    'loan_already_been_returned' => 'Empréstimo já foi devolvido',

    'and_more_error' => '(e mais :amount erro)',
    'and_more_errors' => '(e mais :amount erros)',

    'invalid_signature' => 'Assinatura inválida',
    'error_decoding_base64' => 'Erro ao decodificar a base64',

];
