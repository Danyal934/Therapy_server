@extends('layouts.app')

@section('title', 'Private Therapy — LCI Wellbeing')
@section('meta_description', 'Private therapy with LCI Wellbeing: a confidential, trauma-informed space to understand what you\'re carrying and move through it safely.')

@section('content')

    <section class="bg-cream px-6 pt-16 pb-16 sm:pt-20">
        <div class="mx-auto max-w-4xl">
            <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-gold">Private Therapy</p>
            <h1 class="mt-4 font-serif text-3xl font-semibold leading-tight text-ink sm:text-4xl">
                A confidential space to come home to yourself.
            </h1>
            <p class="mt-6 font-sans text-base leading-7 text-ink-soft sm:text-lg">
                Private therapy offers a confidential space to step away from the noise and
                reconnect with yourself. This is a space to understand what you're carrying —
                and begin to move through it safely.
            </p>
        </div>
    </section>

    <x-section tone="paper" eyebrow="Fit" title="Who it's for">
        <p class="max-w-3xl font-sans text-base leading-7 text-ink-soft sm:text-lg">This is for individuals who:</p>
        <ul class="mt-5 grid gap-4 sm:grid-cols-2">
            @foreach([
                'Appear to be coping on the outside, but feel overwhelmed within',
                'Are navigating anxiety, stress, or emotional exhaustion',
                'Want to understand themselves more deeply',
                'Are ready to make meaningful internal change',
            ] as $item)
                <li class="flex items-start gap-3 rounded-xl bg-cream px-5 py-4 ring-1 ring-hairline">
                    <span class="mt-1 h-1.5 w-1.5 flex-none rounded-full bg-gold"></span>
                    <span class="font-sans text-sm leading-6 text-ink-soft">{{ $item }}</span>
                </li>
            @endforeach
        </ul>
    </x-section>

    <x-section eyebrow="Method" title="How I work">
        <p class="max-w-3xl font-sans text-base leading-7 text-ink-soft sm:text-lg">
            My approach is trauma-informed, grounded, and focused on emotional regulation and
            self-awareness. We work at your pace, creating a space that feels safe, steady, and
            contained. The aim is not just to talk — but to help you understand, process, and
            move forward.
        </p>
    </x-section>

    <x-section tone="paper" eyebrow="Focus" title="Areas I support">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                'Anxiety and stress',
                'Trauma and emotional overwhelm',
                'Workplace pressure and burnout',
                'Relationship challenges',
                'Personal growth and self-understanding',
                'Grief and loss',
            ] as $area)
                <x-card class="!p-5">
                    <p class="font-serif text-base font-semibold text-ink">{{ $area }}</p>
                </x-card>
            @endforeach
        </div>
    </x-section>

    <x-section eyebrow="Practical Details" title="Locations, availability & fees">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="space-y-6">
                <x-card class="!p-6">
                    <h3 class="font-serif text-lg font-semibold text-ink">Session Format</h3>
                    <ul class="mt-4 space-y-3 font-sans text-sm leading-6 text-ink-soft">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 h-2 w-2 flex-none rounded-full bg-gold"></span>
                            In-person sessions available in [Location 1]
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 h-2 w-2 flex-none rounded-full bg-gold"></span>
                            Online sessions via secure video platform
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 h-2 w-2 flex-none rounded-full bg-gold"></span>
                            Flexible appointment times including evenings
                        </li>
                    </ul>
                </x-card>

                <x-card class="!p-6">
                    <h3 class="font-serif text-lg font-semibold text-ink">Fees</h3>
                    <div class="mt-4 space-y-2 font-sans text-sm leading-6 text-ink-soft">
                        <p><strong>Initial consultation</strong>: [Fee]</p>
                        <p><strong>Ongoing sessions</strong>: [Fee] per 50-minute session</p>
                    </div>
                </x-card>
            </div>
            <div class="space-y-6">
                <x-card class="!p-6">
                    <h3 class="font-serif text-lg font-semibold text-ink">Insurance & Payment</h3>
                    <div class="mt-4 space-y-3 font-sans text-sm leading-6 text-ink-soft">
                        <p>I work with several major insurance providers. Please contact me for details about your specific provider.</p>
                        <p>Payment can be made by bank transfer or card.</p>
                    </div>
                </x-card>

                <x-card class="!p-6">
                    <h3 class="font-serif text-lg font-semibold text-ink">Next Steps</h3>
                    <div class="mt-4">
                        <x-button href="{{ route('contact') }}" variant="primary" class="!w-full justify-center">Enquire about therapy</x-button>
                    </div>
                </x-card>
            </div>
        </div>
    </x-section>

    <section class="bg-cream-deep px-6 py-16">
        <div class="mx-auto max-w-4xl rounded-[2rem] bg-paper/60 backdrop-blur-xl p-10 text-center ring-1 ring-hairline/70 shadow-[0_25px_50px_-20px_rgba(43,38,32,0.25)] sm:p-12">
            <p class="font-script italic text-2xl text-gold">Ready when you are.</p>
            <h2 class="mt-3 font-serif text-2xl font-semibold text-ink sm:text-3xl">Begin an initial session</h2>
            <p class="mt-3 font-sans text-base leading-7 text-ink-soft sm:text-lg">
                If you feel ready to begin, you can get in touch to arrange an initial session.
            </p>
            <p class="mt-3 font-script italic text-lg text-gold">Come home to yourself.</p>
            <div class="mt-7">
                <x-button href="{{ route('contact') }}">Book / Contact</x-button>
            </div>
        </div>
    </section>

@endsection
