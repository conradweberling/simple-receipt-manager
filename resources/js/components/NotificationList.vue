<template>

    <div>
        <b-overlay :show="loading">

            <template #overlay>
                <div class="position-absolute" style="top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);">
                    <span aria-hidden="true" class="spinner-border"></span>
                </div>
            </template>

            <b-alert :show="showAlert" :variant="alert.variant">{{ alert.text }}</b-alert>

            <b-card
                v-for="notification in notifications"
                v-bind:key="notification.id"
                class="mb-4"
            >

                <b-row>
                    <b-col>
                        <b-card-title>
                            {{notification.data.subject}}
                            <span class="badge badge-primary" v-if="notification.read_at === null">New</span>
                        </b-card-title>
                    </b-col>
                    <b-col cols="3" md="2" class="text-right">
                        <button class="btn btn-toggle-nav py-0" @click="destroyNotification(notification.id)">
                            <b-icon icon="trash" scale="1" variant="danger"></b-icon>
                        </button>
                    </b-col>
                </b-row>
                <hr>
                <b-card-text>{{notification.data.message}}</b-card-text>
                <hr>
                <b-card-text class="small text-muted">{{ formatTime(notification.created_at) }}</b-card-text>

            </b-card>

            <p v-if="noItemsFound">No notifications could be found.</p>

        </b-overlay>
    </div>

</template>

<script>
import moment from 'moment';
import { BIconTrash } from 'bootstrap-vue'
export default {
        components: {
            BIconTrash
        },
        props: {
            route: {
                required: true
            },
            update: {
                required: true
            },
            destroy: {
                required: true
            },
            open: {
                required: true
            }
        },
        data() {
            return {
                notifications: [],
                loading: false,
                error: false,
                alert: {
                    text: '',
                    variant: 'danger'
                }
            }
        },
        methods: {
            getNotifications() {

                this.loading = true;

                axios.get(this.route)
                    .then(response => {

                        if(this.error) this.error = false;
                        if(this.alert.text) this.alert.text = '';

                        this.notifications = response.data[0];
                        this.loading = false;

                        if(this.existsNewNotifications) this.updateNotifications(); //mark all

                    }).catch(() => {

                        this.error = true;
                        this.loading = false;

                    });

            },
            updateNotifications() {

                axios.post(this.update);

            },
            destroyNotification(id) {

                this.loading = true;

                axios.post(this.destroy.replace('replaceid', id))
                    .then(response => {

                        let key = this.notifications.findIndex( x => x.id === id );
                        this.notifications.splice(key, 1); //delete key
                        this.loading = false;

                    }).catch(() => {

                        this.error = true;
                        this.loading = false;

                    });

            },

            formatTime(value) {
                return moment(value).utc().format("YYYY-MM-DD HH:mm");
            }

        },
        computed: {
            noItemsFound() {
                return (!this.error && !this.notifications.length && !this.loading);
            },
            showAlert() {
                return !!(this.alert.text.length);
            },
            newNotifications() {
                return this.notifications.filter((elem) => {
                    if (elem.read_at === null) return elem;
                });
            },
            existsNewNotifications() {
                return this.newNotifications.length > 0;
            }
        },
        watch: {
            open: {
                handler: function(value) {
                    if(value) this.getNotifications();
                }
            }
        }

    }
</script>

<style scoped>

.card-title {
    margin-bottom: 0;
}

div > .position-absolute {
    padding-top: 1.5rem !important;
}

</style>
