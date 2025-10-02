@extends("admin.pages.app")

@section("content")
<div class="container py-5">
    <h2 class="mb-4">{{ __("Dashboard") }}</h2>

    <div class="row g-4">
        <div class="col-md-3">
            <a href="{{ route("admin.users.index") }}">
            <div class="card p-4 text-center shadow-sm">
                <h6 class="text-muted">{{ __("Number of Users") }}</h6>
                <h2 class="text-primary">{{ $usersCount }}</h2>
            </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route("admin.admins.index") }}">
            <div class="card p-4 text-center shadow-sm">
                <h6 class="text-muted">{{ __("Number of Admins") }}</h6>
                <h2 class="text-success">{{ $adminsCount }}</h2>
            </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="">
            <div class="card p-4 text-center shadow-sm">
                <h6 class="text-muted">{{ __("Number of Levels") }}</h6>
                <h2 class="text-warning">{{ $levelCount }}</h2>
            </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route("admin.questions.index") }}">
            <div class="card p-4 text-center shadow-sm">
                <h6 class="text-muted">{{ __("Number of Questions") }}</h6>
                <h2 class="text-warning">{{ $questionCount }}</h2>
            </div>
            </a>
        </div>
    </div>
</div>

<div class="container py-5">
    <h2 class="mb-4">{{ __("Schools Statistics") }}</h2>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h6 class="mb-3">{{ __("Top 5 Schools by User Count") }}</h6>
                <canvas id="topSchoolsChart" height="200"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h6 class="mb-3">{{ __("Lowest 5 Schools by User Count") }}</h6>
                <canvas id="lowSchoolsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Top Schools Chart
    const topSchoolsData = {
        labels: {!! $topSchools->pluck("school") !!},
        datasets: [{
            label: '{{ __("Users Count") }}',
            data: {!! $topSchools->pluck("users_count") !!},
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };
    new Chart(document.getElementById('topSchoolsChart').getContext('2d'), {
        type: 'bar',
        data: topSchoolsData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: '{{ __("Top 5 Schools by User Count") }}'
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Lowest Schools Chart
    const lowSchoolsData = {
        labels: {!! $lowSchools->pluck("school") !!},
        datasets: [{
            label: '{{ __("Users Count") }}',
            data: {!! $lowSchools->pluck("users_count") !!},
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    };
    new Chart(document.getElementById('lowSchoolsChart').getContext('2d'), {
        type: 'bar',
        data: lowSchoolsData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: '{{ __("Lowest 5 Schools by User Count") }}'
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
