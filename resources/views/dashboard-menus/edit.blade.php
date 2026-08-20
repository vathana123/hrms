@extends('layouts.app')

@section('content')
    <x-page-header :title="'Edit Dashboard Menu: ' . $dashboardMenu->name" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <form action="{{ route('dashboard-menus.update', $dashboardMenu->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <x-form.input name="name" label="Name" placeholder="Name" required :value="$dashboardMenu->name" />
                    <x-form.input name="local_name" label="Local Name" placeholder="Local Name" :value="$dashboardMenu->local_name" />
                    <x-form.input name="icon" label="Icon" placeholder="Icon" value="{!! $dashboardMenu->icon !!}" />
                    <x-form.input name="permission" label="Permission" placeholder="Permission" :value="$dashboardMenu->permission" />
                    <x-form.input name="route_url" label="Route URL" placeholder="Route URL" :value="$dashboardMenu->route_url" />
                    <x-form.select name="type" label="Type" :options="\App\Enums\MenuType::options()" required :value="$dashboardMenu->type->value" />
                    <x-form.select name="open_in_new_tab" label="Open in New Tab" :options="['1' => 'Yes', '0' => 'No']" required
                        :value="$dashboardMenu->open_in_new_tab" />
                    <x-form.input name="display_ordering" label="Order" type="number" required :value="$dashboardMenu->display_ordering" />
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    @can('user-delete')
                        <button type="button" class="qbtn qbtn-red" data-toggle="qmodal" data-target="#delete">
                            Delete
                        </button>
                    @endcan

                    <button type="submit" class="qbtn qbtn-blue">
                        Save Change
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('modals')
    <x-delete-modal id="delete" :action="route('dashboard-menus.destroy', $dashboardMenu->id)" :message="'Do you really want to delete Dashboard Menu ' . $dashboardMenu->name . '?'" />
@endsection
