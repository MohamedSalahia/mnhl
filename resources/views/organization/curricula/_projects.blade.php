@foreach ($projects as $project)
    <option value="{{ $project->id }}"
            data-levels-url="{{ route('organization.projects.levels', $project) }}"
    >
        {{ $project->name }}
    </option>
@endforeach
