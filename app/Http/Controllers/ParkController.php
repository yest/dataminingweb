<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Map;
use Illuminate\Support\Facades\DB;

class ParkController extends Controller
{
    private $markers = ['40.854316, -73.876790', '40.782857, -73.965334','40.865443, -73.809131', '40.896376, -73.887741'];

    public function index() {
        $config['center'] = '40.832231, -73.885977';
        $config['zoom'] = '12';
        $config['disableDefaultUI'] = true;
        Map::initialize($config);
        $data['map'] = Map::create_map();
        return view('park', $data);
    }

    public function workout() {
        $sentiments = DB::table('tweets')
            ->select(DB::raw('parkname, sum(sentiment) as sentiment, count(id) as num, AVG(sentiment) as average'))
            ->where('keyword','workout')
            ->groupBy('parkname')
            ->orderBy('parkname')
            ->get();

        $config['center'] = '40.832231, -73.885977';
        $config['zoom'] = '12';
        $config['disableDefaultUI'] = true;
        Map::initialize($config);
        $i = 0;
        foreach ($sentiments as $value) {
            $marker = array();
            $marker['position'] = $this->markers[$i];
            $average = $value->average;
            $marker['icon'] = $this->markerSelection($average);
            $marker['infowindow_content'] =
                'Positive: ' . $value->sentiment . '<br />'.
                'Negative: ' . ($value->num - $value->sentiment). '<br />'.
                'Sentiment: ' . $average;
            Map::add_marker($marker);
            $i++;
        }

        $data['map'] = Map::create_map();

        return view('park', $data);
    }

    public function socializing() {
        $sentiments = DB::table('tweets')
            ->select(DB::raw('parkname, sum(sentiment) as sentiment, count(id) as num, AVG(sentiment) as average'))
            ->where('keyword','socializing')
            ->groupBy('parkname')
            ->orderBy('parkname')
            ->get();

        $config['center'] = '40.832231, -73.885977';
        $config['zoom'] = '12';
        $config['disableDefaultUI'] = true;
        Map::initialize($config);
        $i = 0;
        foreach ($sentiments as $value) {
            $marker = array();
            $marker['position'] = $this->markers[$i];
            $average = $value->average;
            $marker['icon'] = $this->markerSelection($average);
            $marker['infowindow_content'] =
                'Positive: ' . $value->sentiment . '<br />'.
                'Negative: ' . ($value->num - $value->sentiment). '<br />'.
                'Sentiment: ' . $average;
            Map::add_marker($marker);
            $i++;
        }

        $data['map'] = Map::create_map();

        return view('park', $data);
    }

    public function relaxation() {
        $sentiments = DB::table('tweets')
            ->select(DB::raw('parkname, sum(sentiment) as sentiment, count(id) as num, AVG(sentiment) as average'))
            ->where('keyword','relaxation')
            ->groupBy('parkname')
            ->orderBy('parkname')
            ->get();

        $config['center'] = '40.832231, -73.885977';
        $config['zoom'] = '12';
        $config['disableDefaultUI'] = true;
        Map::initialize($config);

        $i = 0;
        foreach ($sentiments as $value) {
            $marker = array();
            $marker['position'] = $this->markers[$i];
            $average = $value->average;
            $marker['icon'] = $this->markerSelection($average);
            $marker['infowindow_content'] =
                'Positive: ' . $value->sentiment . '<br />'.
                'Negative: ' . ($value->num - $value->sentiment). '<br />'.
                'Sentiment: ' . $average;
            Map::add_marker($marker);
            $i++;
        }

        $data['map'] = Map::create_map();

        return view('park', $data);
    }

    private function markerSelection($average) {
        $marker = '';
        if($average >= 0.75) {
            $marker = 'image/smile.png';
        } elseif ($average >= 0.5) {
            $marker = 'image/happy.png';
        } elseif ($average >= 0.25) {
            $marker = 'image/straight.png';
        } else {
            $marker = 'image/sad.png';
        }
        return $marker;
    }
}
