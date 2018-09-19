<template>
    <div class="card d-flex flex-column align-items-center">
        <a class="title" v-on:click="show(modal)" :href="tabId">{{ factor.name }}</a>
        <div class="score">
            <radial-progress-bar :diameter="120"
                       :completed-steps="score"
                       :total-steps="100"
                       innerStrokeColor="#c2d6d6"
                       startColor="#00cc00"
                       stopColor="#0099ff">
                <p style="font-size: 1.2rem; font-weight: bold;">{{ (score / 10).toFixed(1) }} / 10</p>
            </radial-progress-bar>
        </div>
        <div class="info d-inline">
            <div class="title text-center">優劣分析</div>
            <ul class="content">
                <li v-for="(li, index) in contents" :key="index">{{ li }}</li>
            </ul>
        </div>
    </div>
</template>
<script>
import RadialProgressBar from 'vue-radial-progress';

export default {
    data: function() {
        return {
            tabId: '/#' + (this.factor.enName || 'safety')
        }
    },
    computed: {
    	contents: function() {
    		let have = [];
            let notHave = [];
            let result = [];
            let fc = this.factor;
    		for(let i = 0; i < fc.details.length; i++) {
    			if(fc.details[i].features.length <= 0) {
                    notHave.push('附近無 ' + fc.details[i].title);
                }
                else {
    			    let feature = fc.details[i].features[0];
    			    let s = '近 ' + feature.properties.title + ' ' + feature.properties.name;
    			    have.push(s);
                }
    		}
    		return have.concat(notHave) || ['查無資料'];
    	},
        score: function() {
            let fs = parseInt(this.factor.score);
            return fs;
        }
    },
    methods: {
        show: function(modal) {
            this.$modal.show(modal);
        }
    },
    components: {
        RadialProgressBar,
    },
    props: {
        factor: {
            type: Object,
            default: function() {
                return {
                    name: 'factor',
                    score: 0,
                    details: null,
                    enName: 'safety',
                }
            }
        },
        modal: {
            type: String,
            default: function() {
                return "factors-detail";
            }
        },
    }
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
        color: rgb(79, 98, 40);
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
