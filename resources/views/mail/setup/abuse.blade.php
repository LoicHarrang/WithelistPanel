@component('mail::message')
# Votre compte a été désactivé

Nous vous écrivons pour vous informer que votre compte a été désactivé sur la base d'une de vos réponses au test écrit.

Voici la réponse qui a engendré votre suspension:

@component('mail::panel')
    {{ $answer }}
@endcomponent

La raison indiquée par le superviseur pour votre suspension est la suivante:

@component('mail::panel')
    {{ $reason }}
@endcomponent

En principe, les sanctions pour ce genre de choses sont généralement fermes.

Pour plus d'informations sur ce qu'il faut faire, allez sur le panel.

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
