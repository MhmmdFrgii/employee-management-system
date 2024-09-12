<div class="col-md-12">
    <h5 class="mt-4">Proyek Dengan Tenggat Waktu Terdekat</h5>
    <div class="list-group">
        @forelse ($projectsWithNearestDeadlines as $project)
            @php
                $endDate = \Carbon\Carbon::parse($project->end_date);
                $now = \Carbon\Carbon::now();

                $daysRemaining = $now->diffInDays($endDate, false);

                $isUrgent = $daysRemaining <= 20;
                $cardClass = $isUrgent ? 'card-danger' : 'card-primary';
            @endphp
            <a href="{{ route('projects.show', ['project' => $project->id]) }}"
                class="list-group-item list-group-item-action {{ $cardClass }}">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $project->name }}</h5>
                    <small>{{ $endDate->locale('id')->diffForHumans() }}</small>
                </div>
                <small class="text-muted">Tenggat Waktu: {{ $endDate->format('d M Y') }}</small>
            </a>
        @empty
            <p class="list-group-item">Tidak ada proyek dengan tenggat waktu yang akan datang.</p>
        @endforelse
    </div>
</div>
