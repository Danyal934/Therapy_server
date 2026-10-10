<div {{ $attributes->merge(['class' => 'rounded-2xl bg-paper/60 backdrop-blur-xl p-6 shadow-2xl ring-1 ring-hairline/70 hover:shadow-[0_25px_50px_-12px_rgba(43,38,32,0.25)] transition-all duration-400 hover:-translate-y-1.5 hover:bg-paper/70']) }}>
    {{ $slot }}
</div>

