<x-admin-layout :drk="true" title="Dashboard">
  <x-admin.frame>
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
      <x-admin.title>Analytics</x-admin.title>

      <form method="POST" action="">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <div>
            <x-label.main for="from_date">From Date</x-label.main>
            <x-input.default type="date" name="from_date" value="{{ $fromDate }}" class="w-full mt-2" />
          </div>
          <div>
            <x-label.main for="to_date">To Date</x-label.main>
            <x-input.default type="date" name="to_date" value="{{ $toDate }}" class="w-full mt-2" />
          </div>
          <div class="flex items-end">
            <button type="submit" class="bg-primary w-full text-white py-2 px-4 rounded-xl">Filter</button>
          </div>
        </div>
      </form>

      <div class="flex flex-col lg:flex-row gap-4 mt-8">
        <div class="w-full lg:w-1/2">
          <canvas id="transactionChart" class="w-full"></canvas>
        </div>
        <div class="w-full lg:w-1/2">
          <canvas id="profitChart" class="w-full"></canvas>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
        @foreach ($analyticsData as $key => $data)
          <div class="bg-[{{ $data['color'] }}] text-white rounded-lg p-4 lg:p-8">
            <p>{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
            <h5>{{ $data['decimal'] ? '₦' . number_format($data['value'], 2) : number_format($data['value']) }} </h5>
          </div>
        @endforeach
      </div>
      <div class="p-4"></div>  
    </main>
  </x-admin.frame>
</x-admin-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const transactionCtx = document.getElementById('transactionChart').getContext('2d');
    const profitCtx = document.getElementById('profitChart').getContext('2d');
    const dailyTransactions = @json(array_values($dailyTransactions));
    const dailyProfits = @json(array_values($dailyProfits));
    const labels = @json(array_keys($dailyTransactions));

    function formatCurrency(value) {
        return '₦' + value.toLocaleString();
    }

    new Chart(transactionCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Daily Transactions',
                data: dailyTransactions,
                borderColor: 'orange',
                backgroundColor: 'rgba(255,165,0,0.5)',
                borderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuad'
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return formatCurrency(tooltipItem.raw);
                        }
                    }
                }
            }
        }
    });

    new Chart(profitCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Daily Profits',
                data: dailyProfits,
                borderColor: 'green',
                backgroundColor: 'rgba(0,128,0,0.5)',
                borderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuad'
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return formatCurrency(tooltipItem.raw);
                        }
                    }
                }
            }
        }
    });
});
</script>