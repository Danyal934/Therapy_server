@extends('admin.layout')

@section('title', 'Edit Reflection')

@section('content')
    <div class="card">
        <div class="row" style="justify-content: space-between; align-items: center;">
            <div>
                <div class="muted">Reflection #{{ $reflection->id }}</div>
                <div style="font-size: 20px; font-weight: 700;">{{ $reflection->title }}</div>
            </div>
            <div class="row" style="gap: 8px;">
                <a class="btn secondary" href="{{ route('admin.reflections.index') }}">Back</a>
                <form method="POST" action="{{ route('admin.reflections.destroy', $reflection) }}" onsubmit="return confirm('Delete this item?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn danger" type="submit">Delete</button>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.reflections.update', $reflection) }}" enctype="multipart/form-data" style="margin-top: 12px;">
            @csrf
            @method('PUT')

            <!-- Title row -->
            <div class="row" style="margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Title</label>
                    <input class="input" type="text" name="title" value="{{ old('title', $reflection->title) }}" required style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <!-- Premium checkbox row -->
            <div class="row" style="margin-bottom: 16px;">
                <label class="row" style="gap: 8px; align-items: center;">
                    <input type="checkbox" name="premium" value="1" @checked(old('premium', $reflection->premium))>
                    <span>Premium (store audio privately)</span>
                </label>
            </div>

            <!-- Picture and Audio File row -->
            <div class="row" style="gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Picture</label>
                    <div class="muted" style="margin: 6px 0; word-break: break-all;">{{ $reflection->pic }}</div>
                    <input class="input" type="file" name="pic" accept="image/*" style="width:100%; box-sizing: border-box;">
                </div>
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Audio File</label>
                    <div class="muted" style="margin: 6px 0; word-break: break-all;">{{ $reflection->audio_file }}</div>
                    <input class="input" type="file" name="audio_file" accept="audio/*" style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <div class="row" style="margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Subheadings</label>
                    <div class="muted" style="margin-bottom: 6px;">One per line</div>
                    <textarea class="input" name="subheadings" rows="4" style="width:100%; box-sizing: border-box; resize: vertical;">@if(is_array($reflection->subheadings)){{ implode("\n", $reflection->subheadings) }}@endif</textarea>
                </div>
            </div>

            <div class="row" style="margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Journal Prompts</label>
                    <div class="muted" style="margin-bottom: 6px;">One per line</div>
                    <textarea class="input" name="prompts" rows="6" style="width:100%; box-sizing: border-box; resize: vertical;">@if(is_array($reflection->journal_prompts)){{ implode("\n", $reflection->journal_prompts) }}@endif</textarea>
                </div>
            </div>

            <!-- Save button row -->
            <div class="row" style="margin-top: 8px;">
                <button class="btn" type="submit">Save</button>
            </div>
        </form>
    </div>
@endsection
