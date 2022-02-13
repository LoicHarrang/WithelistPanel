@component('mail::message')
# @lang('setup.email.notification.mail.heading')

@lang('setup.email.notification.mail.paragraph')

@component('mail::button', ['url' => route('verify', ['code' => $user->email_verified_token])])
@lang('setup.email.notification.mail.button')
@endcomponent

@lang('setup.email.notification.mail.expiration')

@lang('setup.email.notification.mail.footer', ['name' => config('app.name')])
@endcomponent
