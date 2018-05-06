<template>
    <div class="summary-card d-flex flex-column align-items-center">
        <div class="title">綜合分數</div>
        <div class="score">
            <vue-circle :progress="score * 10" :size="150" :reverse="false" line-cap="round" empty-fill="rgba(0, 0, 0, .1)" :animation-start-value="0.0" :start-angle="-1/2 * Math.PI" insert-mode="append" :thickness="15" :show-percent="false">
                <p style="font-size: 1.3rem; font-weight: bold;">{{ score.toFixed(1) }} / 10</p>
            </vue-circle>
        </div>
        <div class="info d-inline">
            <div class="title text-center">房屋簡歷</div>
            <ul class="content">
                <li>平均房價：53.2萬 / 坪</li>
                <li>平均坪數：26.8 坪</li>
                <li>平均屋齡：18年</li>
            </ul>
        </div>
    </div>
</template>
<script>
import VueCircle from 'vue2-circle-progress';
export default {
    data: function() {
        return {
            score: 77 / 10
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