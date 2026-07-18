@foreach ($admin->roles as $role)
    @if ($role->name == 'clinic_admin')
        <h6><span class="badge badge-primary">Clinic admin</span></h6>
    @else
        <h6><span class="badge badge-primary">{{ $role->name }}</span></h6>
    @endif
@endforeach
