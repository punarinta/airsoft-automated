@extends('layout')

@section('content')
<div class="padded_content" style="max-width:800px">
    <h3>About</h3>
    <p>
        I'm playing airsoft for 10 years and programming for 15 years. I'm bad at design, but not so bad at UX.
        The idea of this service is to automate as much in airsoft as possible, helping both those who play and those who arrange games.
        I've started with some basic things, so don't expect too much right now. But the development is ongoing all the time and I'm always opened for your proposals.
    </p>

    <h3>Cookies</h3>
    <p>
        Yes, cookies are used here. I have to inform you about it according to the law. The only purpose for them to be used is to know if you are logged in or not.
        Information about your activity is not used anywhere else except the internals of this site. If you are still afraid, use "incognito" mode of your web-browser.
    </p>

    <h3>Liability disclaimer</h3>
    <p>
        I'm not responsible for any losses you had due to the usage of this system. That's so simple.
    </p>

    <h3>Copyright notice</h3>
    <p>
        You are free to copy any information from this site until you are not charging money for its further distribution.
        In case you want to charge please contact <a href="mailto:{{ Config::get('app.emails.office') }}?subject=Data usage">{{ Config::get('app.emails.office') }}</a> for further instructions.
        In some time I'll even add API to help you distributing data.
    </p>
</div>

@stop