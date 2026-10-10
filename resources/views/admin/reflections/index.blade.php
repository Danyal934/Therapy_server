@extends('admin.layout')

@section('title', 'Reflections')

@section('content')
    <div class="row" style="justify-content: space-between; margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.reflections.index') }}" class="row">
            <input class="input" type="text" name="q" placeholder="Search title" value="{{ $q ?? '' }}">
            <select name="premium" class="input">
                <option value="">All</option>
                <option value="1" @selected(($premiumFlag ?? '') === '1')>Premium</option>
                <option value="0" @selected(($premiumFlag ?? '') === '0')>Free</option>
            </select>
            <button class="btn" type="submit">Filter</button>
            <a class="btn secondary" href="{{ route('admin.reflections.index') }}">Reset</a>
        </form>
        <a class="btn" href="{{ route('admin.reflections.create') }}">New</a>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Type</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($reflections as $reflection)
                <tr>
                    <td>{{ $reflection->id }}</td>
                    <td>{{ $reflection->title }}</td>
                    <td>
                        @if ($reflection->premium)
                            <span class="badge green">premium</span>
                        @else
                            <span class="badge">free</span>
                        @endif
                    </td>
                    <td><a href="{{ route('admin.reflections.edit', $reflection) }}">Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="margin-top: 12px;">
            {{ $reflections->links('admin.pagination') }}
        </div>
    </div>
@endsection
