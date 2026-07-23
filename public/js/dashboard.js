// Chart.js Entegrasyonu
document.addEventListener('DOMContentLoaded', function() {
    const chartCanvas = document.getElementById('taskChart');
    if (!chartCanvas) return;
    
    const ctx = chartCanvas.getContext('2d');
    
    // Grafik içi yumuşak geçişli arka plan rengi
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(31, 201, 221, 0.4)'); // accent color
    gradient.addColorStop(1, 'rgba(31, 201, 221, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Completed Tasks',
                data: [12, 19, 15, 25, 22, 30, 28],
                borderColor: '#1fc9dd',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#1b5fc5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4 // Çizgilerin yumuşak kavis alması için
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(23, 23, 23, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#1fc9dd',
                    borderColor: '#333',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Tasks Completed';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(102, 102, 102, 0.15)', // secondary with opacity
                        drawBorder: false
                    },
                    ticks: {
                        color: '#dedede',
                        padding: 10,
                        stepSize: 5
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#dedede',
                        padding: 10
                    }
                }
            }
        }
    });
});
