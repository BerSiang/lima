<template>
    <div class="card d-flex flex-column align-items-center">
        <div class="title">{{ factor.name }}</div>
        <div class="score">
            <vue-circle :progress="score * 10" :size="100" :reverse="false" line-cap="round" empty-fill="rgba(0, 0, 0, .1)" :animation-start-value="0.0" :start-angle="-1/2 * Math.PI" insert-mode="append" :thickness="10" :show-percent="false">
                <p style="font-size: 1.3rem; font-weight: bold;">{{ score.toFixed(1) }} / 10</p>
            </vue-circle>
        </div>
        <div class="info d-inline">
            <div class="title text-center">優劣分析</div>
            <ul class="content">
                <li>距離公園 承德公園260 m</li>
                <li>距離士林運動中心 250 m</li>
                <li>距離KTV 好樂迪 開車16min</li>
                <li>距離百貨公司 開車10min</li>
                <li>距離電影院 陽明劇院 500m</li>
            </ul>
        </div>
    </div>
</template>
<script>
import VueCircle from 'vue2-circle-progress';
export default {
    data: function() {
        return {
            score: 50 / 10
        }
    },
    watch: {
        /*address: function() {
            this.findLocation();
        }*/
    },
    methods: {},
    components: {
        VueCircle
    },
    props: ['factor']
}
</script>
<style lang="scss" scoped>
.card {
    padding: 0;
    background: rgb(219, 243, 191);
    border-color: rgb(185, 208, 140);
    border-style: solid;
    border-radius: 0.5rem;
    color: rgb(79, 98, 40);
    font-weight: bold;

    .title {
        font-size: 1.2rem;
    }
    .info {
        width: 100%;
        .title {
            font-size: 1.4rem;
            background: rgb(188, 233, 134);
        }
        .content {
            max-height: 6rem;
            min-height: 5rem;
            overflow-y: auto;
            font-size: 1rem;
            list-style-type: none;
            padding: 1rem;
        }
    }
}
</style>