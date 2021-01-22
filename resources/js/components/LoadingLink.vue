<template>

    <b-overlay :show="loading" class="d-inline-block w-100" spinner-small>
        <a
            :class="bclass"
            :href="bhref"
            @click="loading=true"
        >
            <slot></slot>
        </a>
    </b-overlay>


</template>

<script>
    export default {

        props: {
            bclass: {
                default: ""
            },
            bhref: {
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
