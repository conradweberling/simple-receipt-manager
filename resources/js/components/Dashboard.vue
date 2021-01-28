<template>

    <div>

        <div class="row mb-4 mt-2">

            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <button
                        ref="left"
                        class="btn btn-dashboard p-0 w-100 h-100"
                        @click="clickedLeft()"
                        :disabled="disabledLeft"
                    >
                        <b-icon icon="chevron-left" scale="1.25"></b-icon>
                    </button>
                </div>
            </div>
            <div class="col-8">
                <h4 class="text-center">{{ currentData.month }}</h4>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <button
                        ref="right" class="btn btn-dashboard p-0 w-100 h-100"
                        @click="clickedRight()"
                        :disabled="disabledRight"
                    >
                        <b-icon icon="chevron-right" scale="1.25"></b-icon>
                    </button>
                </div>
            </div>

        </div>

        <doughnut-chart
            :total="currentData.total"
            :chartData="getChartData(currentData.amounts, currentData.colors)"
        ></doughnut-chart>

        <div class="mt-5 mx-1" v-if="loaded === true">
            <div class="row h-100 m-0 mb-2 align-items-center" v-for="(name, index) in currentData.names">

                <div class="col-4 p-0">
                    <svg height="30" width="60">
                        <rect width="60" height="30" :style="'fill:'+currentData.colors[index]"/>
                    </svg>
                </div>

                <div class="col-4 p-0">
                    <h5 class="text-center mb-0">{{ name }}</h5>
                </div>

                <div class="col-4 p-0">
                    <h5 class="text-center mb-0">
                        {{ currentData.amounts[index].toString().replace('.', ',') }}€
                    </h5>
                </div>

            </div>

        </div>

    </div>

</template>

<script>
    import moment from 'moment';
    export default {

        props: {
            route: {
                required: true,
            }
        },
        data() {
            return {
                selected: 0,
                total: 1,
                current: 0,
                page: 1,
                perPage: 6,
                loaded: null,
                currentData: {},
                data: [
                    {
                        month: "",
                        total: 0,
                        amounts: [],
                        colors: [],
                        names: [],
                    }
                ]
            }
        },
        created() {
            this.currentData = this.data[this.selected];
            this.loadData();
        },
        methods:  {
            clickedRight() {

                let self = this;
                setTimeout(function(){self.$refs.right.blur();},50);

                if(!this.disabledRight) this.selected--;
            },
            clickedLeft() {

                let self = this;
                setTimeout(function(){self.$refs.left.blur();},50);

                if(!this.disabledLeft) {
                    let new_selected = this.selected + 1;
                    if(this.data[new_selected] === undefined) {
                        this.page++;
                        this.loadData(new_selected);
                    } else {
                        this.selected = new_selected;
                    }

                }
            },
            getChartData(amounts, colors) {
                return {
                    datasets: [
                        {
                            data: amounts,
                            backgroundColor: colors,
                            borderWidth: 0
                        }
                    ]
                };
            },
            loadData(selected=0) {

                this.$emit('loading', true)
                let self = this;

                axios.get(this.route+'?page='+this.page)
                    .then((response) => {

                        if(response.data.total !== 0) {

                            self.data = (self.page === 1) ?
                                self.data = response.data.data :
                                self.data.concat.apply(self.data, response.data.data);

                            self.total = response.data.total;
                            self.current = self.current + response.data.perPage;

                            if(selected) self.selected = selected;
                            self.currentData = self.data[self.selected];

                            self.loaded = true;

                        } else {

                            self.loaded = false;

                        }

                        self.showEmptyChart();
                        self.$emit('loading', false)

                    }).catch(() => {

                        self.loaded = false;
                        self.showEmptyChart();
                        self.$emit('loading', false)

                    });

            },
            showEmptyChart() {

                if(this.loaded === false) {

                    this.currentData = {
                        month: moment().format("MMMM YYYY"),
                        total: 0,
                        amounts: [1],
                        colors: ["#212529"],
                        names: [""],
                    };

                }

            }
        },
        computed: {
            disabledRight() {
                return (this.selected === 0);
            },
            disabledLeft() {
                return ((this.selected+1) === this.total);
            }
        },
        watch: {
            selected: {
                handler: function(value) {
                    this.currentData = this.data[value];
                }
            }
        }


    }
</script>

<style scoped>

    svg {
        display: block;
        margin: auto;
    }
    .btn.disabled, .btn:disabled {
        opacity: 0.1 !important;
    }

</style>
