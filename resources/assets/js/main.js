//import './bootstrap';

import _ from 'lodash';
import axios from 'axios';
import Vue from 'vue';
import * as VueGoogleMaps from 'vue2-google-maps';
import { swiper, swiperSlide } from 'vue-awesome-swiper';

import 'swiper/dist/css/swiper.css'

import SearchInput from './components/SearchInput.vue';
import OptionBox from './components/OptionBox.vue';
import FactorCard from './components/FactorCard.vue';
import WeatherCard from './components/WeatherCard.vue';
import SummaryCard from './components/SummaryCard.vue';
import FactorTab from './components/FactorTab.vue';
import FactorDetailModal from './components/FactorDetailModal.vue';
import VModal from 'vue-js-modal';
import {Tabs, Tab} from 'vue-tabs-component';
import vueSlider from 'vue-slider-component';

Vue.use(VModal);
Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyDaOfML_tYJo6Vi_6BKA0CKIyWmQTnAm1U',
        libraries: 'places', // This is required if you use the Autocomplete plugin
        // OR: libraries: 'places,drawing'
        // OR: libraries: 'places,drawing,visualization'
        // (as you require)
    }
});

var google = VueGoogleMaps;
console.log(google);

new Vue({
    el: '#root',
    data: {
        hasText: false,
        windowHeight: window.innerHeight + "px",
        showDropdown: false,
        location: { lat: 25.0838492, lng: 121.5615529 },
        queryConfig: {
            safety: {
                weight: 1,
                details: [
                    {
                        name: '警察局',
                        weight: 1,
                        distance: 0.5
                    },
                    {
                        name: '消防局',
                        weight: 1,
                        distance: 1
                    },
                    {
                        name: '消防栓',
                        weight: 1,
                        distance: 0.02
                    },
                ]
            },
            traffic: {
                weight: 1,
                details: [
                    {
                        name: '公車站',
                        weight: 1,
                        distance: 0.5
                    },
                    {
                        name: '捷運站',
                        weight: 1,
                        distance: 1
                    },
                ]
            },
            livelihood: {
                weight: 1,
                details: [
                    {
                        name: '便利商店',
                        weight: 1,
                        distance: 0.5
                    },
                    {
                        name: '中華郵政',
                        weight: 1,
                        distance: 1
                    },
                    {
                        name: '銀行',
                        weight: 1,
                        distance: 0.02
                    },
                    {
                        name: '傳統市場',
                        weight: 1,
                        distance: 0.02
                    },
                ]
            },
            medical: {
                weight: 1,
                details: [
                    {
                        name: '藥局',
                        weight: 1,
                        distance: 0.5
                    },
                    {
                        name: '醫療單位',
                        weight: 1,
                        distance: 1
                    },
                ]
            },
            leisure: {
                weight: 1,
                details: [
                    {
                        name: '河濱公園',
                        weight: 1,
                        distance: 0.5
                    }
                ]
            },
            nimby: {
                weight: 1,
                details: [
                    {
                        name: '加油站',
                        weight: 1,
                        distance: 0.5
                    },
                    {
                        name: '機場',
                        weight: 1,
                        distance: 1
                    },
                    {
                        name: '工廠',
                        weight: 1,
                        distance: 0.02
                    },
                    {
                        name: '垃圾焚化廠',
                        weight: 1,
                        distance: 0.02
                    },
                    {
                        name: '廢棄物處理場',
                        weight: 1,
                        distance: 1
                    },
                    {
                        name: '煤氣行',
                        weight: 1,
                        distance: 0.02
                    },
                    {
                        name: '廟宇',
                        weight: 1,
                        distance: 0.02
                    },
                ]
            },
            humanities: {
                weight: 1,
                details: [
                    {
                        name: '圖書館',
                        weight: 1,
                        distance: 0.5
                    },
                    {
                        name: '博物館',
                        weight: 1,
                        distance: 1
                    },
                    {
                        name: '宗教單位',
                        weight: 1,
                        distance: 0.02
                    },
                    {
                        name: '學校',
                        weight: 1,
                        distance: 0.02
                    },
                ]
            }
        },
        mapOptions: {
            mapTypeControl: false,
            styles: [{
                    "featureType": "poi",
                    "stylers": [{
                        "visibility": "off"
                    }]
                },
                {
                    "featureType": "road.highway",
                    "stylers": [{
                        "visibility": "off"
                    }]
                },
                {
                    "featureType": "transit",
                    "stylers": [{
                        "visibility": "off"
                    }]
                }
            ]
        },
        sliderDistanceOptions: {
            width: "100%",
            height: 8,
            dotSize: 20,
            min: 0,
            max: 5,
            interval: 0.01,
            disabled: false,
            realTime: true,
            lazy: true,
            debug: true,
            show: true,
            tooltip: "always",
            tooltipDir: [
              "bottom",
              "top"
            ],
            piecewise: false,
            style: {
              "marginBottom": "30px"
            },
            bgStyle: {
              "backgroundColor": "#fff",
              "boxShadow": "inset 0.5px 0.5px 3px 1px rgba(0,0,0,.36)"
            },
            sliderStyle: [
              {
                "backgroundColor": "#f05b72"
              },
              {
                "backgroundColor": "#3498db"
              }
            ],
            tooltipStyle: [
              {
                "backgroundColor": "#f05b72",
                "borderColor": "#f05b72"
              },
              {
                "backgroundColor": "#3498db",
                "borderColor": "#3498db"
              }
            ],
            processStyle: {
              "backgroundImage": "-webkit-linear-gradient(left, #f05b72, #3498db)"
            }
      },
        sliderWeightOptions: {
              width: "100%",
              height: 8,
              dotSize: 20,
              min: 0,
              max: 3,
              debug: true,
              realTime: true,
              lazy: true,
              interval: 0.1,
              disabled: false,
              show: true,
              tooltip: "always",
              tooltipDir: [
                "bottom",
                "top"
              ],
              piecewise: false,
              style: {
                "marginBottom": "30px"
              },
              bgStyle: {
                "backgroundColor": "#fff",
                "boxShadow": "inset 0.5px 0.5px 3px 1px rgba(0,0,0,.36)"
              },
              sliderStyle: [
                {
                  "backgroundColor": "#f05b72"
                },
                {
                  "backgroundColor": "#3498db"
                }
              ],
              tooltipStyle: [
                {
                  "backgroundColor": "#f05b72",
                  "borderColor": "#f05b72"
                },
                {
                  "backgroundColor": "#3498db",
                  "borderColor": "#3498db"
                }
              ],
              processStyle: {
                "backgroundImage": "-webkit-linear-gradient(left, #f05b72, #3498db)"
              }
        },
        analysisOptions: {
            data: [{
                    aspect: '居家安全',
                    options: ['警察局', '醫院', '監視系統', '消防局', '診所']
                },
                {
                    aspect: '交通',
                    options: ['公車站', 'Ubike租賃站', '捷運站', '火車站', '交通要道', '聯外橋梁']
                },
                {
                    aspect: '生活機能',
                    options: ['學區', '銀行', '市場', '郵局', '超市', '便利商店']
                },
                {
                    aspect: '娛樂場所',
                    options: ['KTV', '溜冰場', '運動中心', '公園', '電影院', '商圈']
                },
                {
                    aspect: '嫌惡設施',
                    options: ['殯儀館', '垃圾場', '公墓', '大水溝', '焚化爐']
                }
            ]
        },
        factors: [],
        summary: [],
        tabsId: ['safety', 'traffic', 'livelihood', 'medical-resources', 'leisure', 'nimby', 'humanities'],
        swiperOption: {
            pagination: {
                el: '.swiper-pagination',
                type: 'progressbar'
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            slidesPerView: 4,
            slidesPerGroup: 4,
            spaceBetween : 20,
        },

        icon: {
            factory: {
                url: './icons/factory.png',
                scaledSize: {height: 30, width: 30},
                anchor: {x: 0, y:0},
                origin: {x: 0, y:0}
            },
            default: ''
        }

    },
    computed: {
        //google: VueGoogleMaps.gmapApi,
        dropdownAnimate: function() {
            var open = this.hasText;
            if (open) {
                this.showDropdown = open;
            } else {
                _.debounce(function() { this.showDropdown = open; }, 100);
            }
            return { animated: true, slideInDown: open, slideOutUp: !open };
        },
        markers: function() {
            let result = [];
            let that = this;
            this.factors.forEach(function(factor) {
                let type = factor.name || 'tt';
                let details = factor.details;
                if(!details) return;
                details.forEach(function(detail) {
                    let title = detail.title;
                    let features = detail.features;
                    features.forEach(function(feature) {
                        var coordinates = feature.geometry.coordinates;
                        let icon = that.icon.default;
                        /*if(feature.properties.title == "工廠") {
                            icon = that.icon.factory;
                        }*/
                        let marker = {
                            position: {lng: coordinates[0], lat: coordinates[1]},
                            title: feature.properties.title + ' ' + feature.properties.name,
                            type: type,
                            icon: icon
                        }
                        result.push(marker);
                    })
                });
            });
            return result;
        }

    },
    methods: {
        showQueryConfig: function(modal) {
            this.$modal.show(modal);
        },
        getCurrentLocation: function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(this.setCurrentLocation);
            }
        },
        refreshSli: function(sli) {
            this.$nextTick(() => this.$refs.sli.refresh());
        },
        setCurrentLocation: function(position) {
            this.location = { lat: position.coords.latitude, lng: position.coords.longitude };
        },
        dropdown: function(val) {
            if (val) {
                this.showDropdown = val;
            } else {
                _.debounce(function() { this.showDropdown = val; }, 100);
            }
            return { animated: true, slideInDown: val, slideOutUp: !val };
        },
        locatedOnMap(place) {
            //console.log(place);
            this.location = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            };
            this.hasText = true;
            axios.get('/api/factors', {
                params: {
                    'x': this.location.lng,
                    'y': this.location.lat,
                    'config': this.queryConfig
                }
            })
            .then(response => {
                this.factors = null;
                this.summary = response.data;
                this.factors = this.summary.factors;
                console.log("HERE\n" + response.data);
            })
            .catch(function (error) {
                console.log("ERROR\n" + error);
            });

        },
        usePlace(place) {
            if (this.place) {
                this.markers.push({
                    position: {
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng(),
                    }
                })
                this.place = null;
            }
        },
        init() {
            axios.get('/api/factors', {
                params: {
                    'x': this.location.lng,
                    'y': this.location.lat,
                    'config': this.queryConfig
                }
            })
            .then(response => {
                console.log(response.data);
                this.summary = response.data;
                this.factors = this.summary.factors;
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    },
    created: function() {
        this.getCurrentLocation();
        this.init();
    },
    components: {
        SearchInput,
        OptionBox,
        FactorCard,
        WeatherCard,
        SummaryCard,
        swiper,
        swiperSlide,
        Tabs,
        Tab,
        FactorTab,
        FactorDetailModal,
        vueSlider,
        'gmap-marker': VueGoogleMaps.Marker
    }
});
