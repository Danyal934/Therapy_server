@props(['href' => null, 'variant' => 'primary', 'type' => 'button'])

@php
$base = 'inline-flex items-center justify-center gap-2 rounded-full px-7 py-3 text-[13px] font-semibold uppercase tracking-[0.14em] transition-all duration-200';
$variants = [
    'primary' => 'bg-gold text-cream hover:bg-gold-deep shadow-[0_8px_20px_-8px_rgba(180,134,63,0.55)] hover:shadow-[0_10px_24px_-8px_rgba(180,134,63,0.6)] hover:-translate-y-0.5',
    'outline' => 'border border-ink/25 text-ink hover:border-gold hover:text-gold-deep',
    'ghost'   => 'text-gold hover:text-gold-deep',
];
$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
