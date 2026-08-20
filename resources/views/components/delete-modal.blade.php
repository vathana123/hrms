@props([
    'id' => 'delete',
    'action',
    'title' => 'Delete',
    'message' => 'Are you sure?',
])

<div class="qpopup" id="{{ $id }}">
    <div class="qpopup-backdrop"></div>

    <div class="qpopup-content">
        <form action="{{ $action }}" method="POST">
            @csrf
            @method('DELETE')

            <input type="hidden" name="deleted_by" value="{{ auth()->user()->username }}">

            <div class="qpopup-header">
                <h5 class="qpopup-title">{{ $title }}</h5>
                <button type="button" class="qbtn qbtn-icon close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="qpopup-body">
                <p>{{ $message }}</p>
            </div>

            <div class="qpopup-footer justify-content-end">
                <button type="button" class="qbtn close">No</button>
                <button type="submit" class="qbtn qbtn-blue">Yes</button>
            </div>
        </form>
    </div>
</div>