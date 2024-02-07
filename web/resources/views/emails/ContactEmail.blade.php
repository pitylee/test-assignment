@component('mail::message')
    <h2>Greetings,</h2>
    <p>
        You have received a new message!<br/><br/>
        <b>Subject:</b> {{ $data['subject'] }}<br/>
        <b>Message:</b> {!! nl2br(e($data['message'])) !!}<br/><br/><br/>
        Thanks,
        {{ config('app.name') }}
    </p>
@endcomponent
