@props(['paginator'])

@if ($paginator->hasPages())
    <hr>
    <div {{ $attributes->merge(['class' => 'qpagination-summary d-flex justify-content-between align-items-center']) }}>
        {!! $paginator->links('pagination::qpagination') !!}
        <p class="text-secondary m-0">
            Showing {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }}
            of {{ $paginator->total() }} records
        </p>
    </div>
@endif
