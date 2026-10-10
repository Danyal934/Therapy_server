@props([
    'title' => null,
    'subtitle' => null,
    'eyebrow' => null,
    'tone' => null,
])

<section {{ $attributes->merge(['class' => 'py-16 sm:py-20 ' . ($tone === 'paper' ? 'bg-paper' : '')]) }}>
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        @if ($title || $eyebrow)
            <div class="max-w-3xl">
                @if ($eyebrow)
                    <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-gold">{{ $eyebrow }}</p>
                @endif
                @if ($title)
                    <h2 class="mt-2 font-serif text-2xl font-semibold tracking-tight text-ink sm:text-3xl">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p class="mt-3 font-sans text-base leading-7 text-ink-soft sm:text-lg">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="@if($title || $eyebrow) mt-10 @endif">
            {{ $slot }}
        </div>
    </div>
</section>

