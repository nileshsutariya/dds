@include('layouts.header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-6 text-md-start mb-2 mb-md-0">
                <h1 style="font-size: 30px;">Dashboard</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-4">
                <div class="small-box bg-info" style="height: 130px;">
                    <div class="inner text-center">
                        <h4 style="margin-bottom: 5px;">Today</h4>
                        <hr style="border: 1px solid white; margin: 5px 0;">
                        <p style="color: white; font-size: 40px;" id="todayTotalSellingUnit">0</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-4">
                <div class="small-box bg-info" style="height: 130px;">
                    <div class="inner text-center">
                        <h4 style="margin-bottom: 5px;">This Month</h4>
                        <hr style="border: 1px solid white; margin: 5px 0;">
                        <p style="color: white; font-size: 40px;" id="thisMonthTotalSellingUnit">0</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-4">
                <div class="small-box bg-info" style="height: 130px;">
                    <div class="inner text-center">
                        <h4 style="margin-bottom: 5px;">Clients</h4>
                        <hr style="border: 1px solid white; margin: 5px 0;">
                        <p style="color: white; font-size: 40px;" id="totalclients">0</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-12 mb-4">
                <div class="card p-3 h-100">
                    <h5 class>Last 15 Days</h5>
                    <div class="chart-container" style="position: relative;">
                        <canvas id="milkSalesGraph"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12 mb-4">
                <div class="card p-3 h-100">
                    <h5 class>Last 1 Year</h5>
                    <div class="chart-container" style="position: relative;">
                        <canvas id="yearSalesGraph"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        function todaytotalsellingUnit() {
            $.ajax({
                url: "{{ route('today.total.selling.unit') }}",
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    var formattedValue = (data.totalSellingUnit || 0).toFixed(1);
                    $('#todayTotalSellingUnit').text(formattedValue);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching today\'s total selling unit:', error);
                }
            });
        }

        function monthtotalsellingUnit() {
            $.ajax({
                url: "{{ route('month.total.unit') }}",
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    var formattedValue = (data.totalSellingUnit || 0).toFixed(1);
                    $('#thisMonthTotalSellingUnit').text(formattedValue);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching this month\'s total selling unit:', error);
                }
            });
        }

        function totalclients() {
            $.ajax({
                url: "{{ route('total.clients') }}",
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    var formattedValue = (data.totalUnit || 0);
                    $('#totalclients').text(formattedValue);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching total clients:', error);
                }
            });
        }

        todaytotalsellingUnit();
        monthtotalsellingUnit();
        totalclients();
        fetchMilkSales();
        fetchLastYearMilkSales();
    });

    function fetchMilkSales() {
        return $.ajax({
            url: "{{ route('milk.sales.graph') }}",
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            dataType: "json",
        });
    }

    fetchMilkSales().done(function(data) {
        const dates = data.map(function(item) {
            const date = new Date(item.date);
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            return `${month}-${day}`;
        });
        const milkSales = data.map(function(item) {
            return item.unit;
        });

        const ctx = document.getElementById('milkSalesGraph').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Milk Sold (Liters)',
                    data: milkSales,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Milk Sold (Liters)',
                        }
                    }
                }
            }
        });
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error fetching milk sales data:', textStatus, errorThrown);
    });

    function fetchLastYearMilkSales() {
        $.ajax({
            url: "{{ route('year.sales.graph') }}",
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep",
                    "Oct",
                    "Nov", "Dec"
                ];
                const milkSalesByMonth = Array(12).fill(0);

                data.forEach(function(item) {
                    const monthIndex = item.month - 1;
                    milkSalesByMonth[monthIndex] = item.total_milk;
                });

                console.log('Milk Sales By Month:', milkSalesByMonth);

                const ctx = document.getElementById('yearSalesGraph').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: monthNames,
                        datasets: [{
                            label: 'Milk Sold (Liters)',
                            data: milkSalesByMonth,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month',
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Milk Sold (Liters)',
                                }
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            },
            complete: function() {
                console.log('AJAX request completed');
            }
        });
    }
</script>

@include('layouts.footer')
