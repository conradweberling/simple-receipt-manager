<template>

    <div>

        <b-alert :show="showAlert" :variant="alert.variant" dismissible>{{ alert.text }}</b-alert>

        <p v-if="noItemsFound" class="text-center">No pending invitations could be found.</p>

        <b-overlay :show="!requested">

            <div v-if="items.length" class="overflow-auto">

                <b-table
                    id="invitations"
                    :items="items"
                    :fields="fields"
                    :per-page="perPage"
                    :current-page="currentPage"
                    small
                    borderless
                ></b-table>

                <hr class="my-4">

                <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    align="center"
                    aria-controls="invitations"
                ></b-pagination>

            </div>

        </b-overlay>



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
                {
                    key: 'email',
                    label: 'E-Mail'
                },
                {
                    key: 'updated_at',
                    label: 'Time',
                    formatter: (value) => {
                        return moment(value).utc().format("YYYY-MM-DD HH:mm");
                    }
                }
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

        axios.get(this.route).then((response) => {

            this.requested = true;
            this.items = response.data.items;

        }).catch(() => {

            this.requested = true;
            this.error = true;
            this.alert.text = 'Error while loading data!'

        });

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
