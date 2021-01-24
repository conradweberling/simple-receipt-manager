<template>

    <b-overlay :show="loading" :class="'d-inline-block '+oclass" spinner-small>
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
            oclass: {
                default: ""
            },
            bform: {
                required: true
            }
        },
        data() {
            return {
                loading: false
            }
        },
        methods: {
            click() {

                let form = document.getElementById(this.bform);

                if(form.checkValidity()) {
                    this.loading = true;
                    setTimeout(function(){form.submit();},250);
                }

            }
        }

    }
</script>
