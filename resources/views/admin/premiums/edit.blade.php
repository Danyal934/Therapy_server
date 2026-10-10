@extends('admin.layout')

@section('title', 'Edit Premium Audio')

@section('content')
    <div class="card">
        <div class="row" style="justify-content: space-between; align-items: center;">
            <div>
                <div class="muted">Premium #{{ $premium->id }}</div>
                <div style="font-size: 20px; font-weight: 700;">{{ $premium->title }}</div>
            </div>
            <div class="row" style="gap: 8px;">
                <a class="btn secondary" href="{{ route('admin.premiums.index') }}">Back</a>
                <form method="POST" action="{{ route('admin.premiums.destroy', $premium) }}" onsubmit="return confirm('Delete this item?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn danger" type="submit">Delete</button>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.premiums.update', $premium) }}" enctype="multipart/form-data" style="margin-top: 12px;">
            @csrf
            @method('PUT')

            <!-- Title and Category row -->
            <div class="row" style="gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Title</label>
                    <input class="input" type="text" name="title" value="{{ old('title', $premium->title) }}" required style="width:100%; box-sizing: border-box;">
                </div>
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Category</label>
                    <input class="input" type="text" name="category" value="{{ old('category', $premium->category) }}" required style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <!-- Premium checkbox row -->
            <div class="row" style="margin-bottom: 16px;">
                <label class="row" style="gap: 8px; align-items: center;">
                    <input type="checkbox" name="premium" value="1" @checked(old('premium', $premium->premium))>
                    <span>Premium (store audio privately)</span>
                </label>
            </div>

            <!-- Picture and Audio File row -->
            <div class="row" style="gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Picture</label>
                    <div class="muted" style="margin: 6px 0; word-break: break-all;">{{ $premium->pic }}</div>
                    <input class="input" type="file" name="pic" accept="image/*" style="width:100%; box-sizing: border-box;">
                </div>
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Audio File</label>
                    <div class="muted" style="margin: 6px 0; word-break: break-all;">{{ $premium->audio_file }}</div>
                    <input class="input" type="file" name="audio_file" accept="audio/*" style="width:100%; box-sizing: border-box;">
                </div>
            </div>

            <!-- Journal Prompts row -->
            <div class="row" style="margin-bottom: 16px;">
                <div style="flex: 1; min-width: 0;">
                    <label class="muted" style="display: block; margin-bottom: 4px;">Journal Prompts</label>
                    <div class="muted" style="margin-bottom: 6px;">One per line</div>
                    <textarea class="input" name="prompts" rows="6" style="width:100%; box-sizing: border-box; resize: vertical;">@if(is_array($premium->journal_prompts)){{ implode("\n", $premium->journal_prompts) }}@endif</textarea>
                </div>
            </div>

            <!-- Save button row -->
            <div class="row" style="margin-top: 8px;">
                <button class="btn" type="submit" id="saveButton">Save</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('saveButton').addEventListener('click', function() {
            const form = this.closest('form');
            const saveButton = this;
            const originalButtonText = saveButton.textContent;

            form.addEventListener('submit', function() {
                saveButton.disabled = true;
                saveButton.textContent = 'Saving...';
                saveButton.style.opacity = '0.7';
                saveButton.style.cursor = 'not-allowed';
            });
        });
    </script>
@endsection