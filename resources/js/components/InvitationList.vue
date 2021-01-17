<template>

    <div>

        <b-alert :show="showAlert" :variant="alert.variant" dismissible>{{ alert.text }}</b-alert>

        <p v-if="!requested">Loading...</p>

        <p v-if="noItemsFound">No pending invitations could be found.</p>

        <div v-if="items.length" class="overflow-auto">

            <b-table
                id="invitations"
                :items="items"
                :fields="fields"
                :per-page="perPage"
                :current-page="currentPage"
                small
                striped
            ></b-table>

            <b-pagination
                v-model="currentPage"
                :total-rows="rows"
                :per-page="perPage"
                align="right"
                aria-controls="invitations"
            ></b-pagination>

        </div>

    </div>

</template>

<script>
import moment from 'moment';
export default {
    props: {
        route: {
            required: true
        }
    },
    data() {
        return {
            perPage: 10,
            currentPage: 1,
            items: [],
            fields: [
                { key: 'email', label: 'E-Mail'},
                { key: 'updated_at', label: 'Time', formatter: "formatTime"}
            ],
            alert: {
                text: '',
                variant: 'danger'
            },
            error: false,
            requested: false

        }
    },
    mounted() {

        this.axios.get(this.route).then((response) => {

            this.requested = true;
            this.items = response.data.items;

        }).catch(() => {

            this.requested = true;
            this.error = true;
            this.alert.text = 'Error while loading data!'

        });

    },
    methods: {

        formatTime(value) {
            return moment(value).format("YYYY-MM-DD HH:mm");
        }

    },
    computed: {

        rows() {
            return this.items.length;
        },
        noItemsFound() {
            return (this.requested && !this.error && !this.items.length);
        },
        showAlert() {
            return !!(this.alert.text.length);
        }

    }
}
</script>
