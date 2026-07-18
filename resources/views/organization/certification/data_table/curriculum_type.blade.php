@php
    $isMain = $curriculum->curriculum_type === \App\Enums\CurriculumTypeEnum::MAIN;
    $badgeClass = $isMain ? 'badge-primary' : 'badge-warning';
    $label = $isMain ? __('curricula.main') : __('curricula.additional');
@endphp

<span class="badge badge-pill {{ $badgeClass }}">{{ $label }}</span>

