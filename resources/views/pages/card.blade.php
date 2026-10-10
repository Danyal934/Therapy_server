@props(['as' => 'div'])

<{{ $as }} {{ $attributes->merge(['class' => 'rounded-2xl bg-paper p-7 ring-1 ring-hairline shadow-[0_4px_24px_-12px_rgba(43,38,32,0.12)] transition-all duration-300 hover:shadow-[0_12px_32px_-12px_rgba(43,38,32,0.18)] hover:-translate-y-1']) }}>
    {{ $slot }}
</{{ $as }}>
