//import './bootstrap';

import _ from 'lodash';
import Vue from 'vue';
import * as VueGoogleMaps from 'vue2-google-maps';
import { swiper, swiperSlide } from 'vue-awesome-swiper';

import 'swiper/dist/css/swiper.css'

import SearchInput from './components/SearchInput.vue';
import OptionBox from './components/OptionBox.vue';
import FactorCard from './components/FactorCard.vue';
import WeatherCard from './components/WeatherCard.vue';
import SummaryCard from './components/SummaryCard.vue';
//var SearchInput = require('./components/SearchInput.vue');
//Vue.component('search-input', require('./components/SearchInput.vue'));
Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyDaOfML_tYJo6Vi_6BKA0CKIyWmQTnAm1U',
        libraries: 'places', // This is required if you use the Autocomplete plugin
        // OR: libraries: 'places,drawing'
        // OR: libraries: 'places,drawing,visualization'
        // (as you require)
    }
});
new Vue({
    el: '#root',
    data: {
        hasText: false,
        showDropdown: false,
        location: { lat: 25.0838492, lng: 121.5615529 },
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
        factors: [{
                name: '居家安全'
            },
            {
                name: '交通分析'
            },
            {
                name: '民生建築'
            },
            {
                name: '醫療服務'
            },
            {
                name: '娛樂場所'
            },
            {
                name: '嫌惡設施'
            },
            {
                name: '學習資源'
            },
        ],
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
        }
    },
    computed: {
        dropdownAnimate: function() {
            var open = this.hasText;
            if (open) {
                this.showDropdown = open;
            } else {
                _.debounce(function() { this.showDropdown = open; }, 100);
            }
            return { animated: true, slideInDown: open, slideOutUp: !open };
        }
    },
    methods: {
        getCurrentLocation: function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(this.setCurrentLocation);
            }
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
        }
    },
    created: function() {
        this.getCurrentLocation();
    },
    components: {
        SearchInput,
        OptionBox,
        FactorCard,
        WeatherCard,
        SummaryCard,
        swiper,
        swiperSlide,
    }
});