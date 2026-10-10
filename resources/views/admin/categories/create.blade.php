@extends('admin.layout')

@section('title', 'New Category')

@section('content')
    <div class="card">
        <div class="row" style="justify-content: space-between; align-items: center;">
            <div style="font-size: 20px; font-weight: 700;">New Category</div>
            <a class="btn secondary" href="{{ route('admin.categories.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" style="margin-top: 12px;">
            @csrf

            <!-- Name and Active checkbox row -->
            <div class="row" style="gap: 16px; margin-bottom: 16px; align-items: flex-end;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Name</label>
                    <input class="input" type="text" name="name" value="{{ old('name') }}" required style="width:100%; box-sizing: border-box;">
                    <label class="muted" style="display: block; margin: 12px 0 4px;">Sub heading</label>
                    <input class="input" type="text" name="sub_heading" value="{{ old('sub_heading') }}" style="width:100%; box-sizing: border-box;">
                </div>
                <div class="row" style="gap: 8px; align-items: center; padding-bottom: 4px;">
                    <input type="checkbox" name="active" value="1" @checked(old('active'))>
                    <span>Active</span>
                </div>
            </div>

            <!-- Image row -->
            <div class="row" style="margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Image</label>
                    <input class="input" type="file" name="pic" accept="image/*" style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <!-- Audio row -->
            <div class="row" style="margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Audio</label>
                    <input class="input" type="file" name="audio_file" accept="audio/*" style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <!-- Create button row -->
            <div class="row" style="margin-top: 8px;">
                <button class="btn" type="submit">Create</button>
            </div>
        </form>
    </div>
@endsection
