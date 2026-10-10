@props(['title' => null, 'eyebrow' => null, 'tone' => 'cream'])

@php
$bg = $tone === 'deep' ? 'bg-cream-deep' : ($tone === 'paper' ? 'bg-paper' : 'bg-cream');
@endphp

<section {{ $attributes->merge(['class' => "px-6 py-16 sm:py-20 {$bg}"]) }}>
    <div class="mx-auto max-w-6xl">
        @if($eyebrow || $title)
            <div class="mb-10 max-w-2xl">
                @if($eyebrow)
                    <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-gold">{{ $eyebrow }}</p>
                @endif
                @if($title)
                    <h2 class="mt-3 font-serif text-2xl font-semibold text-ink sm:text-3xl">{{ $title }}</h2>
                    <div class="mt-4 h-px w-16 bg-gold/40"></div>
                @endif
            </div>
        @endif
        {{ $slot }}
    </div>
</section>
