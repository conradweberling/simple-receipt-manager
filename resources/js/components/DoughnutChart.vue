<script>
import { Doughnut } from 'vue-chartjs'
export default {
    extends: Doughnut,
    props: {
        total: {
            required: true
        },
        chartData: {
            required: true,
            type: Object
        }
    },

    data() {
        return {
            options: {
                responsive: true,
                events: [],
            }
        }
    },
    methods: {
        textCenter(val) {
            Chart.pluginService.register({
                beforeDraw: function(chart) {

                    var width = chart.chart.width;
                    var height = chart.chart.height;
                    var ctx = chart.chart.ctx;

                    ctx.restore();
                    var fontSize = (height / 150).toFixed(2);
                    ctx.font = "100 " + fontSize + "em Nunito";
                    ctx.textBaseline = "middle";

                    var text = val.toString().replace('.', ',')+'€';
                    var textX = Math.round((width - ctx.measureText(text).width) / 2);
                    var textY = (height / 2)+8;

                    ctx.fillStyle = 'rgb(33,37,41)';
                    ctx.clearRect(0, 0, width, height);
                    ctx.fillText(text, textX, textY);

                    ctx.save();
                }
            });

            Chart.plugins.unregister(this.chartdata);

        }
    },
    watch: {
        chartData: {
            handler: function(value) {
                if(value) {
                    this.renderChart(value, this.options);
                    this.textCenter(this.total)
                }
            }
        }
    }


}
</script>
