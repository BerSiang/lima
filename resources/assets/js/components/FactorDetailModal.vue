<template>
    <modal :name="name" width="70%" height="70%">
        <tabs :options="{ useUrlFragment: false }">
            <tab v-for="(factor, i) in factors" :key="i" :id="factor.enName" :name="factor.name">
                <div class="container">
                    <div class="row">
                        <h1>{{ factor.name }}</h1>
                        <hr />
                        <div class="col-12" v-for="(detail, j) in factor.details" :key="j" v-if="detail">
                            <div>{{detail.title}} 筆數：{{detail.count}} 分數：{{detail.score.toFixed(2)}} 權重：{{detail.weight}} 有效距離：{{detail.effectiveDistance}} KM</div>
                            <div style="padding-bottom: 1rem;" class="row">
                                <div class="col-3" style="padding-left: 1rem;" v-for="(feature, k) in detail.features" :key="k" v-if="feature">
                                    {{feature.properties.name}}
                                </div>
                                <div class="col-3" style="padding-left: 1rem; color: red" v-if="detail.features.length == 0">
                                    本項目無查無資料
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </tab>
                    
        </tabs>
    </modal>
</template>
<script>
//import VModal from 'vue-js-modal';
import {Tabs, Tab} from 'vue-tabs-component';

export default {
    data: function() {
        return {

        }
    },
    props: {
        factors: {
            type: Array,
            default: function() {
                return [];
            }
        },
        name: {
            type: String,
            default: function() {
                return "factors-detail"
            }
        }
    },
    components: {
        Tabs,
        Tab
    }
}
</script>

<style>
.tabs-component {
  height: 100%;
  display: flex;
}

.tabs-component-tabs {
  border: solid 1px #ddd;
  border-radius: 6px;
  margin-bottom: 5px;
}

@media (min-width: 700px) {
  .tabs-component-tabs {
    border: 0;
    align-items: stretch;
    display: flex;
    justify-content: flex-start;
    flex-direction: column;
    margin-bottom: -1px;
    padding-left: 0;
  }
}

.tabs-component-tab {
  color: #999;
  font-size: 14px;
  font-weight: 600;
  margin-right: 0;
  list-style: none;
}

.tabs-component-tab:not(:last-child) {
  border-bottom: dotted 1px #ddd;
}

.tabs-component-tab:hover {
  color: #666;
}

.tabs-component-tab.is-active {
  color: #000;
}

.tabs-component-tab.is-disabled * {
  color: #cdcdcd;
  cursor: not-allowed !important;
}

@media (min-width: 700px) {
  .tabs-component-tab {
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 3px 3px 0 0;
    margin-right: .5em;
    transform: translateY(2px);
    transition: transform .3s ease;
  }

  .tabs-component-tab.is-active {
    border-bottom: solid 1px #fff;
    z-index: 2;
    transform: translateY(0);
  }
}

.tabs-component-tab-a {
  align-items: center;
  color: inherit;
  display: flex;
  writing-mode: vertical-rl;
  padding: .75em 1em;
  text-decoration: none;
}

.tabs-component-panels {
  padding: 4em 0;
  height: 100%;
  width: 100%;
  overflow-y: auto;
}

@media (min-width: 700px) {
  .tabs-component-panels {
    border-top-left-radius: 0;
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 0 6px 6px 6px;
    box-shadow: 0 0 10px rgba(0, 0, 0, .05);
    padding: 4em 2em;
  }
}
</style>

