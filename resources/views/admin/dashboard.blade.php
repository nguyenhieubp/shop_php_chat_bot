@extends('layouts.admin')

@section('title', 'Admin Dashboard Overview')

@section('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    .stat-card {
        background: var(--white);
        border-radius: 24px;
        padding: 30px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: #f8fafc;
        color: var(--primary);
        transition: var(--transition);
    }
    .stat-card:hover .stat-icon {
        background: var(--primary);
        color: white;
    }
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .admin-table th {
        background: #f8fafc;
        padding: 16px 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .admin-table td {
        padding: 18px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        transition: var(--transition);
    }
    .admin-table tr:hover td {
        background: #fcfcfd;
    }
    .order-customer {
        font-weight: 800;
        color: #1e293b;
        font-size: 14px;
    }
    .order-phone {
        font-size: 12px;
        color: #64748b;
        display: block;
        margin-top: 2px;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        gap: 5px;
    }
    .status-new { background: #eef2ff; color: #4338ca; }
    .status-pending { background: #fffbeb; color: #b45309; }
    .status-completed { background: #ecfdf5; color: #059669; }
    .status-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Statistics section styles */
    .stats-container {
        display: flex;
        flex-direction: column;
        gap: 25px;
        margin-bottom: 40px;
    }
    .stats-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }
    .stats-title-group {
        display: flex;
        flex-direction: column;
    }
    .stats-title {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .stats-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 4px;
        font-weight: 500;
    }
    .stats-controls {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    .btn-group {
        display: inline-flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    .btn-group-tab {
        background: transparent;
        border: none;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-group-tab:hover {
        color: #1e293b;
    }
    .btn-group-tab.active {
        background: var(--white);
        color: var(--primary);
        box-shadow: var(--shadow-sm);
    }
    .filter-input {
        padding: 8px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        background: var(--white);
        outline: none;
        transition: var(--transition);
        cursor: pointer;
    }
    .filter-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .stats-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }
    .mini-stat-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 10px;
        transition: var(--transition);
    }
    .mini-stat-card:hover {
        background: var(--white);
        border-color: var(--border);
        box-shadow: var(--shadow-sm);
    }
    .mini-stat-title {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .mini-stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #1e293b;
    }
    .mini-stat-meta {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .progress-bar-container {
        width: 100%;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 5px;
    }
    .progress-bar-fill {
        height: 100%;
        background: var(--primary);
        border-radius: 3px;
        transition: width 0.5s ease-out;
    }
    .chart-container {
        position: relative;
        height: 380px;
        width: 100%;
        margin-top: 15px;
    }
    .toggle-table-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        background: #f8fafc;
        border: 1px dashed var(--border);
        border-radius: 12px;
        padding: 12px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 10px;
    }
    .toggle-table-btn:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }
    .stats-table-wrapper {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-top: 15px;
        display: none;
    }
    .stats-table-wrapper.active {
        display: block;
    }
    .stats-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
    }
    .stats-table th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8fafc;
        box-shadow: 0 1px 0 var(--border);
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
    }
    .stats-table td, .stats-table th {
        padding: 12px 18px;
        font-size: 13px;
        border-bottom: 1px solid var(--border);
        text-align: left;
    }
    .stats-table tr:last-child td {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="admin-header" style="margin-bottom: 40px;">
    <div>
        <h1 class="admin-title" style="font-size: 32px; font-weight: 800;">Tổng Quan Kinh Doanh</h1>
        <p class="admin-subtitle">Chào mừng trở lại! Dưới đây là tóm tắt hoạt động của cửa hàng hôm nay.</p>
    </div>
    <div style="display: flex; gap: 15px;">
        <a href="{{ route('admin.orders') }}" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
            <i class="fa-solid fa-file-invoice"></i> Đơn hàng
        </a>
        <a href="{{ route('admin.product.new') }}" class="btn">
            <i class="fa-solid fa-plus"></i> Sản phẩm
        </a>
    </div>
</div>



<div class="card stats-container">
    <div class="stats-header">
        <div class="stats-title-group">
            <h3 class="stats-title">
                <i class="fa-solid fa-chart-pie" style="color: var(--primary);"></i> Thống Kê Doanh Thu & Đơn Hàng
            </h3>
            <span class="stats-subtitle">Theo dõi số liệu bán hàng và hiệu suất xử lý đơn hàng</span>
        </div>
        <div class="stats-controls">
            <!-- View Selector Tabs -->
            <div class="btn-group" id="stats-type-tabs">
                <button type="button" class="btn-group-tab active" data-type="date">Ngày cụ thể</button>
                <button type="button" class="btn-group-tab" data-type="day">Theo ngày trong tháng</button>
                <button type="button" class="btn-group-tab" data-type="month">Theo tháng</button>
                <button type="button" class="btn-group-tab" data-type="year">Theo năm</button>
            </div>

            <!-- Dynamic Input Filters -->
            <div id="filter-container">
                <!-- For Specific Date stats: Date picker (default visible) -->
                <input type="date" id="filter-date" class="filter-input" value="{{ now()->format('Y-m-d') }}">

                <!-- For Day stats: Month/Year picker (hidden by default) -->
                <input type="month" id="filter-month" class="filter-input" value="{{ now()->format('Y-m') }}" style="display: none;">
                
                <!-- For Month stats: Year selector (hidden by default) -->
                <select id="filter-year" class="filter-input" style="display: none;">
                    @php
                        $currentYear = (int)now()->format('Y');
                    @endphp
                    @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <!-- Mini KPI cards -->
    <div class="stats-summary-grid">
        <div class="mini-stat-card">
            <span class="mini-stat-title">Doanh thu thống kê</span>
            <div class="mini-stat-value" id="summary-revenue">0₫</div>
            <span class="mini-stat-meta" style="color: #10b981;">
                <i class="fa-solid fa-circle-dollar-to-slot"></i> Đơn hoàn thành/đã thanh toán
            </span>
        </div>
        <div class="mini-stat-card">
            <span class="mini-stat-title">Tổng đơn hàng</span>
            <div class="mini-stat-value" id="summary-total-orders">0</div>
            <span class="mini-stat-meta">
                <i class="fa-solid fa-receipt"></i> Tất cả các trạng thái
            </span>
        </div>
        <div class="mini-stat-card">
            <span class="mini-stat-title">Tỷ lệ thành công</span>
            <div>
                <div class="mini-stat-value" id="summary-success-rate">0%</div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" id="summary-success-progress" style="width: 0%"></div>
                </div>
            </div>
            <span class="mini-stat-meta" id="summary-completed-orders">
                0 đơn hoàn thành
            </span>
        </div>
        <div class="mini-stat-card">
            <span class="mini-stat-title">Đơn hàng chờ xử lý</span>
            <div class="mini-stat-value" id="summary-pending-orders">0</div>
            <span class="mini-stat-meta" style="color: #f59e0b;">
                <i class="fa-solid fa-clock-rotate-left"></i> Trạng thái mới/đang xử lý
            </span>
        </div>
    </div>

    <!-- Chart Canvas -->
    <div class="chart-container">
        <canvas id="orderStatsChart"></canvas>
    </div>

    <!-- Expandable Detailed Table -->
    <button type="button" class="toggle-table-btn" onclick="toggleStatsTable()">
        <i class="fa-solid fa-table-list"></i> Xem bảng chi tiết số liệu <i class="fa-solid fa-chevron-down" id="table-chevron"></i>
    </button>
    
    <div class="stats-table-wrapper" id="stats-table-wrapper">
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Doanh thu thực tế</th>
                    <th>Tổng đơn hàng</th>
                    <th>Đơn hoàn thành</th>
                </tr>
            </thead>
            <tbody id="stats-table-body">
                <!-- Dynamically populated -->
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div style="padding: 25px 30px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #fff;">
        <h3 style="font-size: 16px; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-list-check" style="color: var(--primary);"></i> Xử Lý Đơn Hàng Gần Đây
        </h3>
        <a href="{{ route('admin.orders') }}" style="font-size: 13px; font-weight: 700; color: var(--primary); text-decoration: none;">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Sản phẩm đặt mua</th>
                <th>Trạng thái</th>
                <th style="text-align: right; padding-right: 35px;">Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
            <tr>
                <td>
                    <div class="order-customer">{{ $order->customer_name ?? 'Khách lẻ' }}</div>
                    <span class="order-phone">{{ $order->phone }}</span>
                </td>
                <td>
                    <div style="font-weight: 700; color: #475569; font-size: 13px;">{{ $order->product->name }}</div>
                </td>
                <td>
                    @php
                        $statusClass = 'status-new';
                        $statusText = 'Mới nhận';
                        if($order->status == 'pending') { $statusClass = 'status-pending'; $statusText = 'Đang xử lý'; }
                        if($order->status == 'completed') { $statusClass = 'status-completed'; $statusText = 'Hoàn tất'; }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        <span class="status-dot"></span>
                        {{ $statusText }}
                    </span>
                </td>
                <td style="text-align: right; padding-right: 35px;">
                    <div style="font-size: 12px; font-weight: 700; color: #64748b;">
                        {{ $order->created_at->diffForHumans() }}
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 60px 20px;">
                    <div style="opacity: 0.1; margin-bottom: 15px;">
                        <i class="fa-solid fa-inbox" style="font-size: 60px;"></i>
                    </div>
                    <p style="color: #94a3b8; font-weight: 700;">Chưa có đơn hàng nào trong hôm nay.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let statsChart = null;
    let currentType = 'date';

    document.addEventListener('DOMContentLoaded', function() {
        // Initial load
        fetchStats();

        // Type tabs handler
        const tabs = document.querySelectorAll('#stats-type-tabs .btn-group-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                currentType = this.getAttribute('data-type');
                
                // Show/hide filter inputs based on type
                const filterDate = document.getElementById('filter-date');
                const filterMonth = document.getElementById('filter-month');
                const filterYear = document.getElementById('filter-year');
                
                if (currentType === 'date') {
                    filterDate.style.display = 'inline-block';
                    filterMonth.style.display = 'none';
                    filterYear.style.display = 'none';
                } else if (currentType === 'day') {
                    filterDate.style.display = 'none';
                    filterMonth.style.display = 'inline-block';
                    filterYear.style.display = 'none';
                } else if (currentType === 'month') {
                    filterDate.style.display = 'none';
                    filterMonth.style.display = 'none';
                    filterYear.style.display = 'inline-block';
                } else {
                    filterDate.style.display = 'none';
                    filterMonth.style.display = 'none';
                    filterYear.style.display = 'none';
                }
                
                fetchStats();
            });
        });

        // Filters handler
        document.getElementById('filter-date').addEventListener('change', fetchStats);
        document.getElementById('filter-month').addEventListener('change', fetchStats);
        document.getElementById('filter-year').addEventListener('change', fetchStats);
    });

    function toggleStatsTable() {
        const wrapper = document.getElementById('stats-table-wrapper');
        const chevron = document.getElementById('table-chevron');
        wrapper.classList.toggle('active');
        if (wrapper.classList.contains('active')) {
            chevron.className = 'fa-solid fa-chevron-up';
        } else {
            chevron.className = 'fa-solid fa-chevron-down';
        }
    }

    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
            .format(amount)
            .replace('₫', '')
            .trim() + '₫';
    }

    function fetchStats() {
        const dateVal = document.getElementById('filter-date').value;
        const monthVal = document.getElementById('filter-month').value;
        const yearVal = document.getElementById('filter-year').value;
        
        let url = `{{ route('admin.api.order-stats') }}?type=${currentType}`;
        if (currentType === 'date') {
            url += `&date=${dateVal}`;
        } else if (currentType === 'day') {
            url += `&month=${monthVal}`;
        } else if (currentType === 'month') {
            url += `&year=${yearVal}`;
        }

        // Set loading states
        document.getElementById('summary-revenue').textContent = 'Đang tải...';
        document.getElementById('summary-total-orders').textContent = '...';
        document.getElementById('summary-success-rate').textContent = '...%';
        document.getElementById('summary-pending-orders').textContent = '...';

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }
                
                // Update KPI Cards
                document.getElementById('summary-revenue').textContent = formatVND(data.summary.total_revenue);
                document.getElementById('summary-total-orders').textContent = data.summary.total_orders;
                document.getElementById('summary-success-rate').textContent = data.summary.success_rate + '%';
                document.getElementById('summary-success-progress').style.width = data.summary.success_rate + '%';
                document.getElementById('summary-completed-orders').innerHTML = `<i class="fa-solid fa-circle-check"></i> ${data.summary.completed_orders} đơn hoàn thành`;
                document.getElementById('summary-pending-orders').textContent = data.summary.pending_orders;
                
                // Update Chart
                updateChart(data.labels, data.revenue, data.orders);
                
                // Update Table
                updateTable(data.table_data);
            })
            .catch(err => {
                console.error('Error fetching statistics:', err);
            });
    }

    function updateChart(labels, revenueData, orderData) {
        const ctx = document.getElementById('orderStatsChart').getContext('2d');
        
        if (statsChart) {
            statsChart.destroy();
        }
        
        // Premium chart configuration
        statsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Số đơn hàng',
                        data: orderData,
                        type: 'bar',
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y1',
                        order: 2
                    },
                    {
                        label: 'Doanh thu (VND)',
                        data: revenueData,
                        type: 'line',
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.35,
                        yAxisID: 'y',
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                weight: '700',
                                size: 12
                            },
                            color: '#475569',
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: {
                            family: "'Plus Jakarta Sans', sans-serif",
                            weight: '800',
                            size: 13
                        },
                        bodyFont: {
                            family: "'Plus Jakarta Sans', sans-serif",
                            weight: '600',
                            size: 12
                        },
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 1) { // Revenue
                                    label += formatVND(context.parsed.y);
                                } else {
                                    label += context.parsed.y + ' đơn';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                weight: '700',
                                size: 11
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                weight: '600',
                                size: 11
                            },
                            color: '#64748b',
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000) + 'M';
                                } else if (value >= 1000) {
                                    return (value / 1000) + 'K';
                                }
                                return value;
                            }
                        },
                        title: {
                            display: true,
                            text: 'Doanh thu',
                            color: '#ef4444',
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                weight: '800',
                                size: 11
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                weight: '600',
                                size: 11
                            },
                            color: '#64748b',
                            stepSize: 1,
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Số lượng đơn',
                            color: '#3b82f6',
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                weight: '800',
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    function updateTable(tableData) {
        const tbody = document.getElementById('stats-table-body');
        tbody.innerHTML = '';
        
        // Reverse array to show most recent first
        const displayData = [...tableData].reverse();
        
        let hasData = false;
        displayData.forEach(row => {
            if (row.orders > 0 || row.revenue > 0) {
                hasData = true;
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight: 700; color: #1e293b;">${row.time}</td>
                    <td style="font-weight: 700; color: #ef4444;">${formatVND(row.revenue)}</td>
                    <td style="font-weight: 600; color: #475569;">${row.orders} đơn</td>
                    <td>
                        <span class="status-badge status-completed" style="padding: 3px 8px; font-size: 10px;">
                            ${row.completed} thành công
                        </span>
                    </td>
                `;
                tbody.appendChild(tr);
            }
        });

        if (!hasData) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 30px; font-weight: 600;">
                        Không có dữ liệu trong khoảng thời gian này
                    </td>
                </tr>
            `;
        }
    }
</script>
@endsection
