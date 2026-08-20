@extends('layouts.app')

@section('content')
    <x-page-header title="Create Job Position" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <form action="{{ route('job-positions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <x-form.input name="name" label="Name" placeholder="Name" required />
                    <x-form.input name="short_name" label="Short Name" placeholder="Short Name" />
                    <x-form.input name="local_name" label="Local Name" placeholder="Local Name" />
                    <x-form.select name="job_level_id" label="Job Level" :options="$jobLevels" required />
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
