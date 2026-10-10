@extends('admin.layout')

@section('title', 'Premium Audios')

@section('content')
    <div class="row" style="justify-content: space-between; margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.premiums.index') }}" class="row">
            <input class="input" type="text" name="q" placeholder="Search title/category" value="{{ $q ?? '' }}">
            <select name="category" class="input">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" @selected(($categoryFilter ?? '') === $category)>{{ $category }}</option>
                @endforeach
            </select>
            <button class="btn" type="submit">Filter</button>
            <a class="btn secondary" href="{{ route('admin.premiums.index') }}">Reset</a>
        </form>
        <a class="btn" href="{{ route('admin.premiums.create') }}">New</a>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Type</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($premiums as $premium)
                <tr>
                    <td>{{ $premium->id }}</td>
                    <td>{{ $premium->title }}</td>
                    <td>{{ $premium->category }}</td>
                    <td>
                        @if ($premium->premium)
                            <span class="badge green">premium</span>
                        @else
                            <span class="badge">free</span>
                        @endif
                    </td>
                    <td><a href="{{ route('admin.premiums.edit', $premium) }}">Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="margin-top: 12px;">
            {{ $premiums->links('admin.pagination') }}
        </div>
    </div>
@endsection
