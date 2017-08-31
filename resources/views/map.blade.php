<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Harvey emergencies</title>

        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.css" rel="stylesheet">
        <link href="//cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/0.4.0/MarkerCluster.css" rel="stylesheet">
        <link href="//cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/0.4.0/MarkerCluster.Default.css" rel="stylesheet">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css" />

        <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-47255883-3', 'auto');
        ga('send', 'pageview');
        </script>
    </head>
    <body>
    <div class="container-fluid">
    <i id="loading-indicator" class="fa fa-spin fa-4x fa-refresh"></i>
        <div class="panel panel-default hide" id="about">
            <div class="panel-body">
                <a href="#" class="pull-right" id="dismiss-link"><i class="fa fa-close"></i> Dismiss</a>
                <h2>What is this?</h2>

                <p>This app was quickly put together in an effort to help locate people in need, during the devastating floods in Texas.</p>

                <p>The app works as follows:</p>
                <ol>
                    <li>We continously monitor <a href="https://github.com/MiniCodeMonkey/harvey/blob/master/app/Console/Commands/FetchTweetsCommand.php" target="_blank">a set list of twitter handle mentions and hashtags</a></li>
                    <li>In these messages we look for indicaters of addresses</li>
                    <li>If an address is found, we add it to the map</li>
                </ol>

                <p>The process is currently fully automated, so at this time false positives are possible.</p>

                <a href="https://github.com/MiniCodeMonkey/harvey/" target="_blank"><i class="fa fa-github fa-2x pull-right"></i></a>
                
                <p>Questions? Get in touch with us <a href="https://twitter.com/mjwhansen" target="_blank">@mjwhansen</a> or <a href="https://twitter.com/mathiashansen" target="_blank">@mathiashansen</a></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 hidden-xs hidden-sm">
                @foreach (App\Emergency::getCached()->sortByDesc('message.message_created')->take(10) as $emergency)
                    <a href="https://twitter.com/{{ $emergency->message->user_handle }}/status/{{ $emergency->message->twitter_id }}" target="
                    _blank">{{ $emergency->message->message_created->diffForHumans() }}</a>

                    <a href="#{{ $emergency->lat }},{{ $emergency->lng }}" class="marker-link btn btn-default pull-right">
                        <i class="fa fa-search"></i>
                    </a>
                    <blockquote>{{ $emergency->message->message_text }}</blockquote>
                @endforeach
            </div>
            <div id="map" class="col-md-9 col-xs-12"></div>
        </div>
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/0.4.0/leaflet.markercluster.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
