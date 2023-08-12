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
                labels: generateAlphabetArray(dataChartBar.labels.length), // Label inisial
                datasets: [{
                    label: 'Total Transaksi', // Label penuh
                    data: dataChartBar.values,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                hover: {
                    onHover: function(e, elements) {
                        if (elements.length) {
                            var index = elements[0].index;
                            var label = dataChartBar.labels[index]; // Label penuh
                            elements[0]._model.label = label;
                        }
                    }
                },
            }
		});
        }

        const chartData = @json($chartData);
        loadChartBar(chartData);

        function generateAlphabetArray(count) {
            const alphabetArray = [];
            const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            let counter = 0;
            
            for (let i = 0; i < count; i++) {
                const letter = alphabet[i] + (counter > 1 ? counter : '');
                alphabetArray.push(letter);
                
                if (i === count - 1) {
                    i = -1; // Reset to loop back to 'A'
                    counter++;
                }
                
                if (counter > 1 && i === 0) {
                    break;
                }
            }
            
            return alphabetArray;
        }
    </script>
    @endpush
</div>