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
            },
            modal: {
                default: false
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
                    if(this.modal) this.loading = true;
                    this.$emit('loading', true)
                    setTimeout(function(){form.submit();},50);
                }

            }
        }

    }
</script>
