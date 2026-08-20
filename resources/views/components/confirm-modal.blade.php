{{-- resources/views/components/confirm-modal.blade.php --}}

@props([
    'id',
    'title' => 'Confirmation',
    'message' => 'Are you sure?',
    'action',
    'method' => 'POST',
    'submitText' => 'Yes',
    'cancelText' => 'No',
])

<div class="qpopup" id="{{ $id }}">
    <div class="qpopup-backdrop"></div>

    <div class="qpopup-content">
        <form
            action="{{ $action }}"
            method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}"
        >
            @csrf

            @if (!in_array(strtoupper($method), ['GET', 'POST']))
                @method($method)
            @endif

            <div class="qpopup-header">
                <h5 class="qpopup-title">
                    {{ $title }}
                </h5>

                <button
                    type="button"
                    class="qbtn qbtn-icon close"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="qpopup-body">
                <p>
                    {{ $message }}
                </p>

                {{ $slot }}
            </div>

            <div class="qpopup-footer justify-content-end">
                <button
                    type="button"
                    class="qbtn close"
                >
                    {{ $cancelText }}
                </button>

                <button
                    type="submit"
                    class="qbtn qbtn-blue"
                >
                    {{ $submitText }}
                </button>
            </div>
        </form>
    </div>
</div>