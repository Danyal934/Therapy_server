@extends('admin.layout')

@section('title', 'Categories')

@section('content')
    <div class="row" style="justify-content: space-between; margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="row">
            <input class="input" type="text" name="q" placeholder="Search name" value="{{ $q ?? '' }}">
            <select name="active" class="input">
                <option value="">All</option>
                <option value="1" @selected(($active ?? '') === '1')>Active</option>
                <option value="0" @selected(($active ?? '') === '0')>Hidden</option>
            </select>
            <button class="btn" type="submit">Filter</button>
            <a class="btn secondary" href="{{ route('admin.categories.index') }}">Reset</a>
        </form>
        <a class="btn" href="{{ route('admin.categories.create') }}">New</a>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Sub heading</th>
                <th>Status</th>
                <th>Image</th>
                <th>Audio</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td>{{ $cat->name }}</td>
                    <td style="max-width: 360px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $cat->sub_heading ?? '' }}">
                        @if ($cat->sub_heading)
                            {{ $cat->sub_heading }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($cat->active)
                            <span class="badge green">active</span>
                        @else
                            <span class="badge">hidden</span>
                        @endif
                    </td>
                    <td style="max-width: 360px;" title="{{ $cat->pic ?? '' }}">
                        @if ($cat->pic)
                            <a href="{{ route('admin.storage', ['path' => $cat->pic]) }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
                                <img src="{{ route('admin.storage', ['path' => $cat->pic]) }}" alt="{{ $cat->name }}" style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e7eb;">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td style="max-width: 360px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $cat->audio_file ?? '' }}">
                        @if ($cat->audio_file)
                            <a href="{{ asset('storage/'.$cat->audio_file) }}" target="_blank" rel="noopener noreferrer">{{ basename($cat->audio_file) }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td><a href="{{ route('admin.categories.edit', $cat) }}">Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="margin-top: 12px;">
            {{ $categories->links('admin.pagination') }}
        </div>
    </div>
@endsection
