@extends('layouts.app')

@section('content')
    <x-page-header title="Create Lookup Value" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <form action="{{ route('lookup-values.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <x-form.input name="name" label="Name" placeholder="Name" required />
                    <x-form.input name="short_name" label="Short Name" placeholder="Short Name" />
                    <x-form.input name="local_name" label="Local Name" placeholder="Local Name" />
                    <x-form.select name="type" label="Type" :options="\App\Enums\LookupValueType::options()" required />
                    <x-form.input name="display_ordering" label="Order" type="number" />
                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="qbtn qbtn-blue">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        initUserCreateForm();
    </script>
@endsection
