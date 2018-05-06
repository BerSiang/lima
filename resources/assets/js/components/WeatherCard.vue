<template>
    <div class="weather-card align-items-center">
        <div class="title text-center">地區氣候</div>
        <div class="d-flex">
            <ul class="content d-inline">
                <li>夏季均溫：{{ summerAvgTemp }} &deg;C</li>
                <li>冬季均溫：{{ winterAvgTemp }} &deg;C</li>
                <li>年降雨量：{{ annualRainfall }} 毫米</li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            summerAvgTemp: 28.1,
            winterAvgTemp: 15.8,
            annualRainfall: 2094.9
        }
    },
    watch: {
        /*address: function() {
            this.findLocation();
        }*/
    },
    methods: {},
    props: ['weatherData']
}
</script>
<style lang="scss" scoped>
.weather-card {
    width: 100%;
    padding: 0;
    background: rgb(157, 185, 217);
    border-radius: 0.5rem;
    color: white;
    font-size: 1rem;
    font-weight: bold;
    .title {
        font-size: 1.4rem;
        padding: 0.5rem 0;
        border-bottom-style: dotted;
        border-color: rgb(99, 143, 196);
    }
    .content {
        list-style-type: none;
        padding: 10%;
    }
}

.text-center {
    text-align: center;
}
</style>