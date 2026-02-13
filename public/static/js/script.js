document.addEventListener('DOMContentLoaded', () => {

    const dashboardData = {
        cards: {
            newOrders: 150,
            bounceRate: 53,
            userRegistrations: 44,
            uniqueVisitors: 65,
        },
        salesChart: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            sales: [30, 45, 40, 60, 80, 55, 90],
            revenue: [60, 50, 70, 80, 65, 60, 40],
        }
    };

    // isi card
    document.getElementById('newOrders').innerText = dashboardData.cards.newOrders;
    document.getElementById('bounceRate').innerText = dashboardData.cards.bounceRate;
    document.getElementById('userRegistrations').innerText = dashboardData.cards.userRegistrations;
    document.getElementById('uniqueVisitors').innerText = dashboardData.cards.uniqueVisitors;

    // chart
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dashboardData.salesChart.labels,
            datasets: [
                {
                    label: 'Sales',
                    data: dashboardData.salesChart.sales,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,.3)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Revenue',
                    data: dashboardData.salesChart.revenue,
                    borderColor: '#6c757d',
                    backgroundColor: 'rgba(108,117,125,.3)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

});

