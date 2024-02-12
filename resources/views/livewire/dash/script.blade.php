<script>
    document.addEventListener('livewire:load', function() {
        //-------------------------------------------------------------------------------------//
        //                        SALES BY MONTH
        // ------------------------------------------------------------------------------------//
        var options = {
            series: [{
                name: 'Sales of the Month',
                data: @this.salesByMonth_Data
            }],
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return 'USD $' + val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },

            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#E84039',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    formatter: function(val) {
                        return 'USD $' + val;
                    }
                }

            },
            title: {
                text: totalYearSales(),
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#FF5100'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartMonth"), options);
        chart.render();




        //-------------------------------------------------------------------------------------//
        //                        TOP 5 PRODUCTS
        // ------------------------------------------------------------------------------------//
        var optionsTop = {
            series: [
                parseFloat(@this.top5Data[0]['total']),
                parseFloat(@this.top5Data[1]['total']),
                parseFloat(@this.top5Data[2]['total']),
                parseFloat(@this.top5Data[3]['total']),
                parseFloat(@this.top5Data[4]['total'])
            ],
            chart: {
                height: 392,
                type: 'donut',
            },
            labels: [@this.top5Data[0]['product'],
                @this.top5Data[1]['product'],
                @this.top5Data[2]['product'],
                @this.top5Data[3]['product'],
                @this.top5Data[4]['product']
            ],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chartTop5"), optionsTop);
        chart.render();





        //-------------------------------------------------------------------------------------//
        //                                  WEEK SALES
        // ------------------------------------------------------------------------------------//
        var optionsArea = {
            chart: {
                height: 380,
                type: 'area',
                stacked: false,
            },
            stroke: {
                curve: 'straight'
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return '$' + val;
                },
                offsetY: -5,
                style: {
                    fontSize: '12px',
                    colors: ["#FF5100"]
                }
            },
            series: [{
                name: "Day Sale",
                data: [
                    parseFloat(@this.weekSales_Data[0]),
                    parseFloat(@this.weekSales_Data[1]),
                    parseFloat(@this.weekSales_Data[2]),
                    parseFloat(@this.weekSales_Data[3]),
                    parseFloat(@this.weekSales_Data[4]),
                    parseFloat(@this.weekSales_Data[5]),
                    parseFloat(@this.weekSales_Data[6])
                ]
            }, ],
            xaxis: {
                categories: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            },
            tooltip: {
                followCursor: true
            },
            fill: {
                opacity: 5,
            },

        }

        var chartArea = new ApexCharts(
            document.querySelector("#areaChart"),
            optionsArea
        );

        chartArea.render();



        //---------------------------------------------------------------//
        // suma total de ventas durante el año actual
        //---------------------------------------------------------------//
        function totalYearSales() {
            var total = 0
            @this.salesByMonth_Data.forEach(item => {
                total += parseFloat(item)
            })

            return 'Total: USD $' + total.toFixed(2)
        }

    })
</script>