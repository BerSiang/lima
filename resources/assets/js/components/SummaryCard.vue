<template>
    <div class="summary-card d-flex flex-column align-items-center">
        <div class="title">綜合分數</div>
        <div class="score">
            <radial-progress-bar :diameter="120"
                       :completed-steps="score"
                       :total-steps="100"
                       innerStrokeColor="#c2d6d6"
                       startColor="#ff3300"
                       stopColor="#ff9933">
                <p style="font-size: 1.2rem; font-weight: bold;">{{ (score / 10).toFixed(1) }} / 10</p>
            </radial-progress-bar>
        </div>
        <div class="info d-inline">
            <div class="title text-center">房屋簡歷</div>
            <ul class="content">
                <li>土地現值：{{landCurrentValue}}萬 / 坪</li>
                <li>地價：{{declaredValue}}萬 / 坪</li>
            </ul>
        </div>
    </div>
</template>
<script>
import RadialProgressBar from 'vue-radial-progress';

export default {
    data: function() {
        return {
            //score: 77 / 10
        }
    },
    computed: {
        score: function() {
            let fs = parseInt(this.summary.score);
            return fs;
        },
        landCurrentValue: function() {
            let value = this.summary.pracel.landCurrentValue / 0.3025 / 10000;  //m^2 => 坪
            return value.toFixed(2);
        },
        declaredValue: function() {
            let value = this.summary.pracel.declaredValue / 0.3025 / 10000; //m^2 => 坪
            return value.toFixed(2);
        }
    },
    watch: {
        /*address: function() {
            this.findLocation();
        }*/
    },
    methods: {},
    components: {
        RadialProgressBar
    },
    props: {
        summary: {
            type: [Array, Object],
            default: function() {
                return {
                    name: 'summary',
                    score: 0
                }
            }
        }
    }
}
</script>
<style lang="scss" scoped>
.summary-card {
    padding: 0;
    background: rgba(248, 214, 214, 0.45);
    border-radius: 0.5rem;
    color: rgb(207, 35, 35);
    font-weight: bold;

    .title {
        font-size: 1.2rem;
    }
    .info {
        width: 100%;
        .title {
            font-size: 1.4rem;
            background: rgba(239, 163, 163, 0.4);
        }
        .content {
            max-height: 6rem;
            min-height: 5rem;
            overflow-x: hidden;
            font-size: 1rem;
            list-style-type: none;
            padding: 1rem;
        }
    }
}
</style>