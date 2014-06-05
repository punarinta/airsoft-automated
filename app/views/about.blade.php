@extends('layout')

@section('title')
| About
@stop

@section('content')
<div class="padded-content" style="max-width:800px">
    <h3>About</h3>
    <p>
        I'm playing airsoft for 10 years and programming for 15 years. I'm bad at design, but not so bad at software development.
        The idea of this service is to automate as much in airsoft as possible, helping both those who play and those who arrange games.
        I've started with some basic things, so don't expect too much right now. But the development is ongoing all the time and I'm always opened for your proposals.
    </p>

    <h3>Cookies</h3>
    <p>
        Yes, cookies are used here and you have just been informed. The only purpose for them to be used is to know if you are logged in or not.
        Information about your activity is not used anywhere else except the internals of this site. If you are still afraid, use
        <a rel="nofollow" target="_blank" href="https://en.wikipedia.org/wiki/Privacy_mode">"incognito" mode</a> of your web-browser.
    </p>

    <h3>Your private data</h3>
    <p>
        Your private data is yours. No private information is shared, although administrator can access it with maintenance purposes.
        Moreover, this site has SSL encryption which assures that your data reaches the server without being intercepted.
    </p>

    <h3>Liability disclaimer</h3>
    <p>
        I'm not responsible for any losses you had due to the usage of this system. I will try to help you if possible in case you had them, but I bear no legal responsibility. That's so simple.
    </p>

    <h3>Payment providers</h3>
    <p>
        To process payments safely all this job is delegated to so called payment providers. They treat users' data with care and also prevent fraud.
        They charge for it, so even if I'm not charging anything personally (due to a possible agreement with you) payment provider will still take some, usually that's around 3%.
    </p>

    <h3>Copyright notice</h3>
    <p>
        You are free to copy any information from this site until you are not charging money for its further distribution.
        In case you want to charge please contact <a href="mailto:{{ Config::get('app.emails.office') }}?subject=Data usage">{{ Config::get('app.emails.office') }}</a> for further instructions.
        In some time I'll even add API to help you distributing data.
    </p>
</div>
@stop