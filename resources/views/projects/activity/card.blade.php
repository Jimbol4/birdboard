<div class="card">
    <ul>
    @foreach ($project->activity as $activity)
        <li>
            @include("projects.activity.{$activity->description}")
            {{ $activity->created_at->diffForHumans(null, true) }}
        </li>
    @endforeach
    </ul>
</div>