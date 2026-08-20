@props([
    'id',
    'action',
    'title' => 'Filter',
    'width' => null,
])

<div class="qpopup" id="{{ $id }}">

    <div class="qpopup-backdrop"></div>

    <div class="qpopup-content {{ $width == 'wide' ? 'qpopup-wide' : '' }}">
        <form action="{{ $action }}" method="GET">

            <div class="qpopup-header">
                <h5 class="qpopup-title">{{ $title }}</h5>
                <button type="button" class="qbtn qbtn-icon close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="qpopup-body">
                <div class="row">
                    {{ $slot }}
                </div>
            </div>

            <div class="qpopup-footer justify-content-end">
                <button type="button" class="qbtn close">Close</button>
                <button type="submit" class="qbtn qbtn-blue">Search</button>
            </div>

        </form>
    </div>
</div>
