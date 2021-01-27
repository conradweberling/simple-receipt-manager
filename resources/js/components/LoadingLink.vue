<template>

    <button
        :class="bclass"
        @mousedown="click()"
    >
        <slot></slot>
    </button>

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
        methods: {
            click() {

                let self = this;
                this.$emit('loading', true)
                setTimeout(function(){document.location.href = self.bhref;},50);

            }
        }

    }
</script>
