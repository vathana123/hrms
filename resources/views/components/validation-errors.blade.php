@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'qalert mb-2']) }}>
        <div class="qalert-leading">
            <i class="fa-solid fa-info"></i>
        </div>
        <div class="qalert-content">
            <div class="qalert-title">
                Something went wrong
            </div>
            <ul class="qalert-desription">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
