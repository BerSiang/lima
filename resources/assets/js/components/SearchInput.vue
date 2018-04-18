<template>
    <div class="col-md-12 search d-flex justify-content-between">
        <div class="location d-flex">
            <!--<input type="text" v-bind:placeholder="inputHint" v-model="address" />-->
            <GmapAutocomplete :placeholder="inputHint" :value="address" @place_changed="setPlace"></GmapAutocomplete>
            <img class="align-self-center" src="icons/icon.png" />
        </div>
        <a href="#">
            <img class="search-btn" src="icons/search.png" alt="" />
        </a>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            inputHint: "請輸入欲搜尋地點…",
            address: ''
        }
    },
    watch: {
        /*address: function() {
            this.findLocation();
        }*/
    },
    methods: {
        findLocation: _.debounce(
            function() {
                if (this.address.length > 0) {
                    this.$emit('located');
                } else {
                    this.$emit('cleared');
                }
            },
            500
        ),
        setPlace: function(place) {
            this.$emit('located', place);
        }
    }
}
</script>