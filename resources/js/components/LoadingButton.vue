<template>

    <b-overlay :show="loading" class="d-inline-block" spinner-small>
        <button
            :class="bclass"
            :type="btype"
            :onclick="bonclick"
            @click="loading=true"
        >
            <slot></slot>
        </button>
    </b-overlay>


</template>

<script>
    export default {

        props: {
            bclass: {
                default: ""
            },
            btype: {
                default: ""
            },
            bonclick: {
                default: ""
            }
        },
        data() {
            return {
                loading: false
            }
        }

    }
</script>
