@if ($paginator->hasPages())
    <nav class="qpagination">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="disabled">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">&laquo;</a>
        @endif

        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
        @endphp

        {{-- ALWAYS SHOW FIRST --}}
        <a href="{{ $paginator->url(1) }}" class="{{ $current == 1 ? 'active' : '' }}">1</a>

        {{-- CASE 1: START (1 → 4) --}}
        @if ($current <= 4)

            @for ($i = 2; $i <= min(5, $last); $i++)
                <a href="{{ $paginator->url($i) }}" class="{{ $current == $i ? 'active' : '' }}">
                    {{ $i }}
                </a>
            @endfor

            @if ($last > 5)
                <span class="dots">...</span>
                <a href="{{ $paginator->url($last) }}">{{ $last }}</a>
            @endif

        {{-- CASE 2: END (last-3 → last) --}}
        @elseif ($current >= $last - 3)

            <span class="dots">...</span>

            @for ($i = max($last - 4, 2); $i <= $last; $i++)
                <a href="{{ $paginator->url($i) }}" class="{{ $current == $i ? 'active' : '' }}">
                    {{ $i }}
                </a>
            @endfor

        {{-- CASE 3: MIDDLE --}}
        @else

            <span class="dots">...</span>

            @for ($i = $current - 1; $i <= $current + 1; $i++)
                <a href="{{ $paginator->url($i) }}" class="{{ $current == $i ? 'active' : '' }}">
                    {{ $i }}
                </a>
            @endfor

            <span class="dots">...</span>
            <a href="{{ $paginator->url($last) }}">{{ $last }}</a>

        @endif

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">&raquo;</a>
        @else
            <span class="disabled">&raquo;</span>
        @endif

    </nav>
@endif