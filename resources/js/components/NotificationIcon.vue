<template>

    <b-iconstack>
        <b-icon stacked icon="bell"></b-icon>
        <b-icon
            stacked
            v-if="showIcon"
            icon="exclamation-circle-fill"
            variant="danger"
            scale="0.75"
            shift-h="5"
            shift-v="7">
        </b-icon>
    </b-iconstack>

</template>

<script>
    import { BIconstack, BIconBell, BIconExclamationCircleFill } from 'bootstrap-vue'
    export default {
        components: {
            BIconstack,
            BIconBell,
            BIconExclamationCircleFill
        },
        props: {
            default: {
                required: true
            },
            show: {
                required: true
            }
        },
        computed: {
            showIcon() {
                return !!((this.default && this.show !== false) || this.show);
            }
        }
    }


</script>
