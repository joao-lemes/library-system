@component('mail::message')
Olá {{ $userName }},

Você retirou com sucesso o livro intitulado "{{ $bookTitle }}".

Obrigado por usar nosso sistema de biblioteca!

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
