<template>
    <tab id="safety" name="安全">
        <h1>安全</h1>
        <hr />
    </tab>
</template>

<script>
import {Tabs, Tab} from 'vue-tabs-component';

export default {
    data: function() {
        return {
            
        }
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
        id: {
            type: String,
            default: function() {
                return 'factor';
            }
        },
        name: {
            type: String,
            default: function() {
                return 'factor';
            }
        }
    },
    components: {
        Tab
    }
}
</script>
