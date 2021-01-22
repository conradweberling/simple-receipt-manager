<template>

    <b-overlay :show="loading" class="d-inline-block" spinner-small>
        <button
            :class="bclass"
            :type="btype"
            :onclick="bonclick"
            @click="click()"
            @submit="submit()"
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
        },
        methods: {
            click() {
                if(!this.isSubmit) this.handle();
            },
            submit() {
                if(this.isSubmit) this.handle();
            },
            handle() {
                this.loading=true;
            }
        },
        computed: {
            isSubmit() {
                return this.btype === "submit";
            }
        }

    }
</script>
