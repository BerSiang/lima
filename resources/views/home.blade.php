<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LIMA - 宜居新生活</title>
    <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-reboot.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" type="text/css">
</head>

<body>
    <div id="root">
        <gmap-map style="width: 100%; height: 100%; position: absolute; left:0; top:0" :center="location" :zoom="12" :options="mapOptions">
        </gmap-map>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-lg-4 nav">
                    <div class="row" >
                        <div class="col-md-12 head d-flex justify-content-between">
                            <img src="icons/house.png" class="align-self-center" alt="">
                            <div class="title">
                                <span class="logo">LIMA</span>
                                <span class="subtitle">宜居分析地圖–台北市</span>
                            </div>
                            <div class="menu-btn align-self-center">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                        <search-input v-on:located="locatedOnMap" v-on:cleared="hasText = false" @place_changed="locatedOnMap"></search-input>
                        <div class="dropdown" v-show="showDropdown" >
                            <div class="col-md-12 analysis-box" v-bind:class="dropdownAnimate">
                                <option-box v-for="item in analysisOptions.data" :item="item"></option-box>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/manifest.js') }}" defer></script>
    <script src="{{ asset('/js/vendor.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
</body>

</html>