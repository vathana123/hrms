@props([
    'title',
    'back' => false,
    'backUrl' => null,
])

<div {{ $attributes->merge(['class' => 'qpage-header d-flex align-items-center justify-content-between']) }}>
    <div class="qpage-title d-flex gap-2 align-items-center">
        @if ($back)
            <a href="{{ $backUrl ?? url()->previous() }}" class="qbtn qbtn-blue">
                <i class="fa-solid fa-chevron-left me-2"></i>
                <span>Back</span>
            </a>
        @endif

        <h4>{{ $title }}</h4>
    </div>

    @isset($actions)
    @if ($actions->isNotEmpty())
        <div class="qpage-actions d-flex gap-2 align-items-center">
            {{ $actions }}
        </div>
    @endif
    @endisset
</div>

<hr>
