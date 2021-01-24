<template>

    <div>

        <loading-link bclass="btn btn-primary w-100" :bhref="create" oclass="w-100">
            Create Receipt
        </loading-link>

        <hr class="my-4">

        <b-overlay :show="loading">

            <div class="form-group row mb-4">

                <label for="search" class="col-form-label text-md-right"></label>
                <div class="col-8 col-lg-10 pr-1">
                    <input
                        id="search"
                        ref="search"
                        type="text"
                        class="form-control w-100"
                        name="search"
                        v-model="search"
                        @keyup="getResults(1, true)"
                    >
                </div>

                <div class="col-4 col-lg-2 pl-1">
                    <b-overlay :show="loadingSearch" class="d-inline-block w-100" spinner-small>
                        <button
                            class="btn btn-primary w-100"
                            id="searchButton"
                            ref="searchButton"
                            @click="searchButtonClicked"
                        >Search</button>
                    </b-overlay>
                </div>

            </div>

            <hr class="mb-4">

            <b-alert :show="showAlert" :variant="alert.variant">{{ alert.text }}</b-alert>

            <p v-if="noItemsFound">No receipts could be found.</p>

            <div class="card mb-4" v-for="(receipt, index) in receipts">
                <div class="row no-gutters">
                    <div class="col-4 col-lg- 2">

                        <a @click="showImageModal(index)">
                            <img :src="base+'/'+receipt.thumbnail" class="img-fluid" alt="Receipt">
                        </a>

                        <b-modal :ref="'modal-'+index" :title="'Receipt from '+receipt.date" hide-footer>
                            <img :src="base+'/'+receipt.image" class="img-fluid" alt="Receipt">
                        </b-modal>

                    </div>
                    <div class="col-8">
                        <div class="card-body h-100">

                            <div class="row align-items-start h-25">
                                <div class="col text-right">

                                    <button class="btn btn-toggle-nav p-0" @click="showConfirmModal(index)">
                                        <b-icon icon="trash" scale="1" variant="danger"></b-icon>
                                    </button>

                                    <b-modal :id="'confirm-'+index" :ref="'confirm-'+index">

                                        Are you sure you want to delete the receipt?

                                        <template #modal-footer>

                                            <b-button
                                                variant="secondary"
                                                class="float-right"
                                                @click="$bvModal.hide('confirm-'+index)"
                                            >Close
                                            </b-button>

                                            <form :id="'destroy-receipt-'+index" :action="destroy.replace('replaceid', receipt.id)" method="post">

                                                <input name="_token" :value="csrfToken" hidden>

                                                <loading-submit-button
                                                    bclass="btn btn-danger float-right"
                                                    oclass="mt-2"
                                                    :bform="'destroy-receipt-'+index"
                                                >
                                                    Delete
                                                </loading-submit-button>

                                            </form>

                                        </template>

                                    </b-modal>

                                </div>
                            </div>


                            <div class="row align-items-center h-50">
                                <div class="col text-center">
                                    <div class="display-4 display-4-sm">{{receipt.amount}}€</div>
                                    <small class="text-muted">paid by {{receipt.name}} on {{receipt.date}}</small>
                                </div>
                            </div>

                            <div class="row align-items-end h-25"></div>

                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="overflow-auto">

                <b-pagination
                    id="pag"
                    ref="pag"
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="center"
                ></b-pagination>

            </div>

        </b-overlay>

    </div>




</template>

<script>
    export default {
        props: {
            route: {
                required: true
            },
            base: {
                required: true
            },
            destroy: {
                required: true
            },
            create: {
                required: true
            }
        },
        data() {
            return {
                search: '',
                loading: false,
                loadingSearch: false,
                loadingCreate: false,
                receipts: {},
                responseObj: {},
                currentPage: 1,
                error: false,
                alert: {
                    text: '',
                    variant: 'danger'
                }
            }
        },
        created() {
            this.getResults();
        },
        methods: {
            showConfirmModal(index) {
                this.$refs['confirm-'+index][0].show();
            },
            showImageModal(index) {
                this.$refs['modal-'+index][0].show();
            },
            searchButtonClicked() {

                this.getResults(1, true);
                this.$refs.searchButton.blur();

            },
            getResults(page = 1, search = false) {

                if(search) {
                    this.loadingSearch = true;
                    this.currentPage = 1;
                } else {
                    this.loading = true;
                }

                this.axios.get(this.route + '?page=' + page + '&q=' + this.search)
                    .then(response => {

                        this.responseObj = response.data;
                        this.receipts = this.responseObj.data;
                        (search) ? this.loadingSearch = false : this.loading = false;

                        if(this.error) this.error = false;
                        if(this.alert.text) this.alert.text = '';

                    }).catch(() => {

                        this.error = true;
                        this.alert.text = 'Error while loading data!';
                        (search) ? this.loadingSearch = false : this.loading = false;

                    });

            }
        },
        computed: {
            csrfToken() {
                return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            },
            nextPage() {
                return this.currentPage+1;
            },
            perPage() {
                return (this.responseObj.per_page !== undefined) ? this.responseObj.per_page : 20;
            },
            totalRows() {
                return (this.responseObj.total !== undefined) ? this.responseObj.total : 10;
            },
            noItemsFound() {
                return (!this.error && !this.receipts.length && !this.loading);
            },
            showAlert() {
                return !!(this.alert.text.length);
            }
        },
        watch: {
            currentPage: {
                handler: function(value) {
                    if(!this.loadingSearch) {
                        this.getResults(value);
                        window.scroll(0,0);
                    }
                }
            }
        }

    }


</script>

<style scoped>

    @media (max-width: 768px) {
        .display-4-sm {
            font-size: 2.3rem !important;
        }
    }

</style>
