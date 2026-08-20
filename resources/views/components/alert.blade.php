@props([
    'type' => 'success',
    'title' => 'Success',
    'message' => '',
])

<div class="qalert qalert-{{ $type }} popup top show-onload" data-duration="5s">
    <div class="qalert-leading">
        <i class="fa-solid fa-info"></i>
    </div>

    <div class="qalert-content">
        <div class="qalert-title">
            {{ $title }}
        </div>

        <p class="qalert-desription">
            {{ $message }}
        </p>
    </div>

    <div class="qalert-action">
        <button class="qbtn qbtn-icon">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>