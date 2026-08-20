@extends('layouts.app')

@section('content')
    <x-page-header title="Create Dashboard Menu" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <form action="{{ route('dashboard-menus.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <x-form.input name="name" label="Name" placeholder="Name" required />
                    <x-form.input name="local_name" label="Local Name" placeholder="Local Name" />
                    <x-form.input name="icon" label="Icon" placeholder="Icon" />
                    <x-form.input name="permission" label="Permission" placeholder="Permission" />
                    <x-form.input name="route_url" label="Route URL" placeholder="Route URL" />
                    <x-form.select name="type" label="Type" :options="\App\Enums\MenuType::options()" required />
                    <x-form.select name="open_in_new_tab" label="Open in New Tab" :options="['1' => 'Yes', '0' => 'No']" required />
                    <x-form.input name="display_ordering" label="Order" type="number" required />
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
