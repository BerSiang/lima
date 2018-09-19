<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    <!--<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">-->
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge;text/html;charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LIMA - 宜居新生活</title>
    <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-reboot.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <!--<link rel="stylesheet" href="{{ asset('css/swiper.min.css') }}">-->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" type="text/css">
</head>

<body>
    <div id="root">
        <gmap-map style="width: 100%; height: 100%; position: absolute; left:0; top:0" :center="location" :zoom="15" :options="mapOptions">
            <gmap-marker :key="index" v-for="(m, index) in markers" v-bind="m" :clickable="true" :draggable="false" />
        </gmap-map>
        <div class="container-fluid ui-root" :style="{height: windowHeight}">
            <div class="row" style="height: 60%;">
                <div class="col-md-3 col-lg-4 nav">
                    <div class="row">
                        <div class="col-md-12 head d-flex justify-content-between">
                            <img src="icons/house.png" class="align-self-center" alt="">
                            <div class="title">
                                <span class="logo">LIMA</span>
                                <span class="subtitle">宜居分析地圖–台北市</span>
                            </div>
                            <div class="menu-btn align-self-center" v-on:click="showQueryConfig('query-config')">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                        <search-input v-on:located="locatedOnMap" v-on:cleared="hasText = false" @place_changed="locatedOnMap"></search-input>
                        <!--<div class="dropdown" v-show="showDropdown">
                            <div class="col-md-12 analysis-box" v-bind:class="dropdownAnimate">
                                <option-box v-for="item in analysisOptions.data" :item="item"></option-box>
                            </div>
                        </div>-->
                    </div>
                </div>
                <div class="col-md-3 offset-md-6 col-lg-2 offset-lg-6 d-flex flex-column">
                    <weather-card style="margin-bottom: 2rem; font-size: 1.2rem"></weather-card>
                    <summary-card style="" :summary="summary"></summary-card>
                </div>
            </div>
            <div class="row" style="height: 40%;">
                <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2 d-flex align-items-end">
                    <swiper :options="swiperOption">
                        <swiper-slide v-for="(factor, index) in factors" :key="index">
                            <factor-card :factor="factor"></factor-card>
                        </swiper-slide>
                        <div class="swiper-button-prev" slot="button-prev"></div>
                        <div class="swiper-button-next" slot="button-next"></div>
                    </swiper>
                </div>
            </div>
        </div>
        <factor-detail-modal name="factors-detail" :factors="factors">
        </factor-detail-modal>
        <modal name="query-config" width="70%" height="70%">
            <tabs :options="{ useUrlFragment: false }">
                <tab id="q-overview" name="總覽">
                    <h1>分析設定 - 總覽</h1>
                    <hr style="margin-bottom: 3rem;"/>
                    <div class="container-fluid">
                        <div class="row">
                            <span class="col-md-1">安全</span>
                            <vue-slider class="col-md-11" v-bind="sliderWeightOptions" v-model="queryConfig.safety.weight"></vue-slider>
                        </div>
                        <div class="row">
                            <span class="col-md-1">交通</span>
                            <vue-slider class="col-md-11" v-bind="sliderWeightOptions" v-model="queryConfig.traffic.weight"></vue-slider>
                        </div>
                        <div class="row">
                            <span class="col-md-1">民生</span>
                            <vue-slider class="col-md-11" v-bind="sliderWeightOptions" v-model="queryConfig.livelihood.weight"></vue-slider>
                        </div>
                        <div class="row">
                            <span class="col-md-1">醫療</span>
                            <vue-slider class="col-md-11" v-bind="sliderWeightOptions" v-model="queryConfig.medical.weight"></vue-slider>
                        </div>
                        <div class="row">
                            <span class="col-md-1">休閒</span>
                            <vue-slider class="col-md-11" v-bind="sliderWeightOptions" v-model="queryConfig.leisure.weight"></vue-slider>
                        </div>
                        <div class="row">
                            <span class="col-md-1">嫌惡設施</span>
                            <vue-slider class="col-md-11" v-bind="sliderWeightOptions" v-model="queryConfig.nimby.weight"></vue-slider>
                        </div>
                        <div class="row">
                            <span class="col-md-1">人文</span>
                            <vue-slider class="col-md-11" v-bind="sliderWeightOptions" v-model="queryConfig.humanities.weight"></vue-slider>
                        </div>
                    </div>
                </tab>
                <tab id="q-safety" name="安全">
                    <h1>分析設定 - 安全</h1>
                    <hr style="margin-bottom: 3rem;"/>
                    <div class="container-fluid">
                        <div class="row" v-for="q in queryConfig.safety.details">
                            <span class="col-md-1">@{{q.name}}</span>
                            <div class="col-1">權重</div>
                            <vue-slider class="col-md-10" v-bind="sliderWeightOptions" v-model="q.weight"></vue-slider>

                            <div class="offset-md-1 col-md-1">距離(KM)</div>
                            <vue-slider class="col-md-10" v-bind="sliderDistanceOptions" v-model="q.distance"></vue-slider>
                        </div>
                    </div>
                </tab>
                <tab id="q-traffic" name="交通">
                    <h1>分析設定 - 交通</h1>
                    <hr style="margin-bottom: 3rem;"/>
                    <div class="container-fluid">
                        <div class="row" v-for="q in queryConfig.traffic.details">
                            <span class="col-md-1">@{{q.name}}</span>
                            <div class="col-1">權重</div>
                            <vue-slider class="col-md-10" v-bind="sliderWeightOptions" v-model="q.weight"></vue-slider>

                            <div class="offset-md-1 col-md-1">距離(KM)</div>
                            <vue-slider class="col-md-10" v-bind="sliderDistanceOptions" v-model="q.distance"></vue-slider>
                        </div>
                    </div>
                </tab>
                <tab id="q-livelihood" name="民生">
                    <h1>分析設定 - 民生</h1>
                    <hr style="margin-bottom: 3rem;"/>
                    <div class="container-fluid">
                        <div class="row" v-for="q in queryConfig.livelihood.details">
                            <span class="col-md-1">@{{q.name}}</span>
                            <div class="col-1">權重</div>
                            <vue-slider class="col-md-10" v-bind="sliderWeightOptions" v-model="q.weight"></vue-slider>

                            <div class="offset-md-1 col-md-1">距離(KM)</div>
                            <vue-slider class="col-md-10" v-bind="sliderDistanceOptions" v-model="q.distance"></vue-slider>
                        </div>
                    </div>
                </tab>
                <tab id="q-medical" name="醫療">
                    <h1>分析設定 - 醫療</h1>
                    <hr style="margin-bottom: 3rem;"/>
                    <div class="container-fluid">
                        <div class="row" v-for="q in queryConfig.medical.details">
                            <span class="col-md-1">@{{q.name}}</span>
                            <div class="col-1">權重</div>
                            <vue-slider class="col-md-10" v-bind="sliderWeightOptions" v-model="q.weight"></vue-slider>

                            <div class="offset-md-1 col-md-1">距離(KM)</div>
                            <vue-slider class="col-md-10" v-bind="sliderDistanceOptions" v-model="q.distance"></vue-slider>
                        </div>
                    </div>
                </tab>
                <tab id="q-leisure" name="休閒">
                    <h1>分析設定 - 休閒</h1>
                    <hr style="margin-bottom: 3rem;"/>
                    <div class="container-fluid">
                        <div class="row" v-for="q in queryConfig.leisure.details">
                            <span class="col-md-1">@{{q.name}}</span>
                            <div class="col-1">權重</div>
                            <vue-slider class="col-md-10" v-bind="sliderWeightOptions" v-model="q.weight"></vue-slider>

                            <div class="offset-md-1 col-md-1">距離(KM)</div>
                            <vue-slider class="col-md-10" v-bind="sliderDistanceOptions" v-model="q.distance"></vue-slider>
                        </div>
                    </div>
                </tab>
                <tab id="q-nimby" name="嫌惡設施" v-on:click="refreshSli(sli)">
                    <h1>分析設定 - 休閒</h1>
                    <hr style="margin-bottom: 3rem;"/>
                    <div class="container-fluid">
                        <div class="row" v-for="q in queryConfig.nimby.details">
                            <span class="col-md-1">@{{q.name}}</span>
                            <div class="col-1">權重</div>
                            <vue-slider class="col-md-10" ref="sli" v-bind="sliderWeightOptions" v-model="q.weight"></vue-slider>

                            <div class="offset-md-1 col-md-1">距離(KM)</div>
                            <vue-slider class="col-md-10" v-bind="sliderDistanceOptions" v-model="q.distance"></vue-slider>
                        </div>
                    </div>
                </tab>
                <tab id="q-humanities" name="人文">
                    <h1>分析設定 - 休閒</h1>
                    <hr style="margin-bottom: 3rem;"/>
                    <div class="container-fluid">
                        <div class="row" v-for="q in queryConfig.humanities.details">
                            <span class="col-md-1">@{{q.name}}</span>
                            <div class="col-1">權重</div>
                            <vue-slider class="col-md-10" v-bind="sliderWeightOptions" v-model="q.weight"></vue-slider>

                            <div class="offset-md-1 col-md-1">距離(KM)</div>
                            <vue-slider class="col-md-10" v-bind="sliderDistanceOptions" v-model="q.distance"></vue-slider>
                        </div>
                    </div>
                </tab>
            </tabs>
        </modal>
    </div>
    <script src="{{ asset('/js/manifest.js') }}" defer></script>
    <script src="{{ asset('/js/vendor.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
</body>
</html>
