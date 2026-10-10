@props([
    'href' => null,
    'variant' => 'primary',
])

@php
    $base = 'inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-gold';
    $variants = [
        'primary' => 'bg-gold text-paper shadow-sm hover:bg-gold-deep',
        'outline' => 'bg-transparent text-ink ring-1 ring-hairline shadow-sm hover:bg-gold/10',
        'ghost' => 'bg-transparent text-ink hover:bg-gold/10',
    ];
    $classes = $base.' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

