@extends('layouts/contentNavbarLayout')

@section('title', 'Analytics Dashboard')

@section('vendor-style')
  @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
  @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('content')

  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <form method="GET" action="{{ route('dashboard.analytics') }}" class="d-flex gap-3 flex-grow-1">
              <div style="min-width: 240px;">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-select" onchange="this.form.submit()">
                  <option value="">All Departments</option>
                  @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $selectedDepartment == $dept->id ? 'selected' : '' }}>
                      {{ $dept->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </form>

            <a href="{{ route('dashboard.analytics.pdf') . ($selectedDepartment ? '?department_id=' . $selectedDepartment : '') }}"
              class="btn btn-primary" target="_blank">
              <i class="bx bx-download"></i> Download PDF
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Sales Trend -->
    <div class="col-lg-6 col-12 mb-4">
      <div class="card">
        <div class="card-header">
          <h5>Sales Trend (Last 12 Months)</h5>
        </div>
        <div class="card-body">
          <div id="salesChart" style="height: 340px;"></div>
        </div>
      </div>
    </div>

    <!-- Expenses Trend -->
    <div class="col-lg-6 col-12 mb-4">
      <div class="card">
        <div class="card-header">
          <h5>Expenses Trend (Last 12 Months)</h5>
        </div>
        <div class="card-body">
          <div id="expensesChart" style="height: 340px;"></div>
        </div>
      </div>
    </div>

    <!-- Comparison Bar -->
    <div class="col-12 mb-4">
      <div class="card">
        <div class="card-header">
          <h5>Sales vs Expenses Comparison</h5>
        </div>
        <div class="card-body">
          <div id="comparisonChart" style="height: 380px;"></div>
        </div>
      </div>
    </div>

    <!-- Donut -->
    <div class="col-lg-6 col-12 mb-4">
      <div class="card">
        <div class="card-header">
          <h5>Financial Overview</h5>
        </div>
        <div class="card-body">
          <div id="donutChart" style="height: 340px;"></div>
        </div>
      </div>
    </div>

    <!-- Department Pie - only when all departments selected -->
    @if (!$selectedDepartment && isset($departmentSalesData) && $departmentSalesData->isNotEmpty())
      <div class="col-lg-6 col-12 mb-4">
        <div class="card">
          <div class="card-header">
            <h5>Sales Distribution by Department</h5>
          </div>
          <div class="card-body">
            <div id="deptPieChart" style="height: 340px;"></div>
          </div>
        </div>
      </div>
    @endif
  </div>

@endsection
@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', () => {

      const months = @json($months);
      const salesData = @json($salesData);
      const expensesData = @json($expensesData);
      const donutData = @json($donutData);
      const donutLabels = @json($donutLabels);

      // Safety check
      if (!Array.isArray(months)) {
        console.error('months is not an array!', months);
      }

      // ── Sales Trend ───────────────────────────────────────
      new ApexCharts(document.querySelector("#salesChart"), {
        series: [{
          name: 'Sales',
          data: salesData
        }],
        chart: {
          type: 'line',
          height: 340,
          toolbar: {
            show: false
          }
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        xaxis: {
          categories: months
        },
        yaxis: {
          title: {
            text: 'PKR'
          }
        },
        colors: ['#28c76f'],
        tooltip: {
          y: {
            formatter: val => "Rs. " + val.toLocaleString()
          }
        }
      }).render();

      // ── Expenses Trend ────────────────────────────────────
      new ApexCharts(document.querySelector("#expensesChart"), {
        series: [{
          name: 'Expenses',
          data: expensesData
        }],
        chart: {
          type: 'line',
          height: 340,
          toolbar: {
            show: false
          }
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        xaxis: {
          categories: months
        },
        yaxis: {
          title: {
            text: 'PKR'
          }
        },
        colors: ['#ea5455'],
        tooltip: {
          y: {
            formatter: val => "Rs. " + val.toLocaleString()
          }
        }
      }).render();

      // ── Comparison Bar ────────────────────────────────────
      new ApexCharts(document.querySelector("#comparisonChart"), {
        series: [{
            name: 'Sales',
            data: salesData
          },
          {
            name: 'Expenses',
            data: expensesData
          }
        ],
        chart: {
          type: 'bar',
          height: 380,
          toolbar: {
            show: false
          }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%'
          }
        },
        xaxis: {
          categories: months
        },
        yaxis: {
          title: {
            text: 'PKR'
          }
        },
        colors: ['#28c76f', '#ea5455'],
        tooltip: {
          y: {
            formatter: val => "Rs. " + val.toLocaleString()
          }
        }
      }).render();

      // ── Financial Donut ───────────────────────────────────
      new ApexCharts(document.querySelector("#donutChart"), {
        series: donutData,
        chart: {
          type: 'donut',
          height: 340
        },
        labels: donutLabels,
        colors: ['#28c76f', '#ea5455', '#ff9f43', '#7367f0'],
        legend: {
          position: 'bottom'
        },
        tooltip: {
          y: {
            formatter: val => "Rs. " + val.toLocaleString()
          }
        }
      }).render();

      // ── Department Pie Chart ── Only when ALL departments are selected ──
      @if (!$selectedDepartment)
        @if (isset($departmentSalesData) && $departmentSalesData->isNotEmpty())
          const deptTotalsRaw = @json($departmentSalesData->pluck('total')->values()->toArray());
          const deptNames = @json($departmentSalesData->pluck('name')->values()->toArray());

          // Convert string values to real numbers (very important!)
          const deptTotals = deptTotalsRaw.map(val => parseFloat(val) || 0);

          console.log('Department Pie Data:', {
            totals: deptTotals,
            names: deptNames
          });

          if (deptTotals.length > 0 && deptNames.length === deptTotals.length) {
            new ApexCharts(document.querySelector("#deptPieChart"), {
              series: deptTotals,
              chart: {
                type: 'pie',
                height: 340
              },
              labels: deptNames,
              colors: ['#7367f0', '#28c76f', '#ff9f43', '#ea5455', '#00cfe8', '#4b5563'],
              legend: {
                position: 'bottom'
              },
              tooltip: {
                y: {
                  formatter: val => "Rs. " + val.toLocaleString()
                }
              }
            }).render();
          } else {
            console.warn('Department pie chart: data length mismatch');
          }
        @else
          console.warn('No department sales data available');
        @endif
      @endif

    });
  </script>
@endsection
