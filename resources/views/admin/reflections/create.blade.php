@extends('admin.layout')

@section('title', 'New Reflection')

@section('content')
    <div class="card">
        <div class="row" style="justify-content: space-between; align-items: center;">
            <div style="font-size: 20px; font-weight: 700;">New Reflection</div>
            <a class="btn secondary" href="{{ route('admin.reflections.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('admin.reflections.store') }}" enctype="multipart/form-data" style="margin-top: 12px;">
            @csrf

            <!-- Title row -->
            <div class="row" style="margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Title</label>
                    <input class="input" type="text" name="title" value="{{ old('title') }}" required style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <!-- Premium checkbox row -->
            <div class="row" style="margin-bottom: 16px;">
                <label class="row" style="gap: 8px; align-items: center;">
                    <input type="checkbox" name="premium" value="1" @checked(old('premium'))>
                    <span>Premium (store audio privately)</span>
                </label>
            </div>

            <!-- Picture and Audio File row -->
            <div class="row" style="gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Picture</label>
                    <input class="input" type="file" name="pic" accept="image/*" required style="width:100%; box-sizing: border-box;">
                </div>
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Audio File</label>
                    <input class="input" type="file" name="audio_file" accept="audio/*" required style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <div class="row" style="margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Subheadings</label>
                    <div class="muted" style="margin-bottom: 6px;">One per line</div>
                    <textarea class="input" name="subheadings" rows="4" style="width:100%; box-sizing: border-box; resize: vertical;">{{ old('subheadings') }}</textarea>
                </div>
            </div>

            <div class="row" style="margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Journal Prompts</label>
                    <div class="muted" style="margin-bottom: 6px;">One per line</div>
                    <textarea class="input" name="prompts" rows="6" style="width:100%; box-sizing: border-box; resize: vertical;">{{ old('prompts') }}</textarea>
                </div>
            </div>

            <!-- Create button row -->
            <div class="row" style="margin-top: 8px;">
                <button class="btn" type="submit">Create</button>
            </div>
        </form>
    </div>
@endsection
