<template>

    <b-overlay :show="loading" :class="overlayClass" spinner-small>
        <button
            :class="bclass"
            @mousedown="click()"
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
            bhref: {
                default: ""
            },
            oclass: {
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

                let self = this;
                this.loading=true;
                setTimeout(function(){document.location.href = self.bhref;},50);

            }
        },
        computed: {
            overlayClass() {
                return 'd-inline-block '+this.oclass;
            }
        }

    }
</script>
