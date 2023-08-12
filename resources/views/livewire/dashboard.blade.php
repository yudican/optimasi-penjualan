<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="header-title">Data Transaksi</h1>
                </div>
                <div class="card-body">
                    {{-- <div id="chart" style="height: 300px;"></div> --}}
                    <canvas id="mhs-chart" style="height: 500px;"></canvas>
                </div>
                <div class="card-footer">
                    <span>Grafik Data Transaksi</span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script>
        function loadChartBar(dataChartBar) {
            console.log(dataChartBar,'dataChartBar')
            var barChart = document.getElementById('mhs-chart').getContext('2d')
            var myBarChart = new Chart(barChart, {
			type: 'bar',
			data: {
				labels: dataChartBar.labels,
				datasets : [{
					label: "Transaksi 2021",
					backgroundColor: 'rgb(23, 125, 255)',
					borderColor: 'rgb(23, 125, 255)',
					data: dataChartBar.values,
				}],
			},
			options: {
				responsive: true, 
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				},
               
                


			}
		});
        }

        const chartData = @json($chartData);
        loadChartBar(chartData);
    </script>
    @endpush
</div>