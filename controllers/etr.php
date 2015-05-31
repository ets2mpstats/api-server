<?php

$this->respond('GET', '/?', function ($request, $response, $service, $app) {

    echo "foo!";

});

// routes to /etr/nowplaying/[:site]
$this->respond('GET', '/nowplaying/[:site]', function ($request, $response, $service, $app) {

    echo "foo!";
    die();
    if ($request->site == 'en') {
        $crawler = Requests::get('http://radio.eurotruckradio.com:8002/stats?sid=1');
    } elseif ($request->site == 'pl') {
        $crawler = Requests::get('http://radio.eurotruckradio.com:8008/stats?sid=1');
    } else {
        return 'Site does not exist';
    }

    $sxml = simplexml_load_string($crawler->body);
    $song = @$sxml->SONGTITLE;

    if ($request->headers()['accept'] != 'application/json') {
        if ($request->site == 'en') {
            $song = "Currently playing on Eurotruckradio.com - ${song}";
        }
        if ($request->site == 'pl') {
            $song = "Teraz gramy na eurotruckradio.pl - ${song}";
        }
        $response->header('Content-Type', 'text/plain');
        echo $song;
    } else {
        $response->header('Content-Type', 'application/json');
        echo json_encode(array('song' => (string)$song));
    }
});
