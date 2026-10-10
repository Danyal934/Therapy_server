@extends('layouts.app')

@section('title', 'LCI Wellbeing Journal — App')
@section('meta_description', 'A guided journaling experience designed to support emotional regulation, reflection, and reconnection.')

@section('content')
    <section class="bg-cream px-6 pt-16 pb-20 sm:pt-20">
        <div class="mx-auto grid max-w-6xl gap-12 lg:grid-cols-2 lg:items-center">
            <div>
                <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-gold">The App</p>
                <h1 class="mt-4 font-serif text-3xl font-semibold leading-tight text-ink sm:text-4xl">
                    The LCI Wellbeing Journal App
                </h1>
                <p class="mt-3 font-script italic text-xl text-gold">Your daily space to pause, reset and reconnect.</p>
                <p class="mt-6 max-w-lg font-sans text-base leading-7 text-ink-soft sm:text-lg">
                    Created and voiced by an experienced psychotherapist, the app offers guided
                    audio practices and journal prompts to help you pause, regulate your emotions,
                    reflect deeply, and reconnect with yourself.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold/10 text-gold ring-1 ring-gold/20">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="font-sans text-sm leading-6 text-ink-soft">Guided audio practices recorded in my own voice</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold/10 text-gold ring-1 ring-gold/20">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="font-sans text-sm leading-6 text-ink-soft">Trauma-informed journaling prompts for reflection</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold/10 text-gold ring-1 ring-gold/20">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="font-sans text-sm leading-6 text-ink-soft">Tools to support emotional regulation anytime, anywhere</p>
                    </div>
                </div>

                <div class="mt-8">
                    <x-button href="{{ route('contact') }}">Explore the App</x-button>
                </div>
            </div>
            <div class="relative mx-auto w-full max-w-xs">
                <div class="absolute -inset-6 -z-10 rounded-[3rem] bg-gold/10 blur-3xl"></div>
                <div class="overflow-hidden rounded-[2.5rem] ring-1 ring-hairline shadow-[0_30px_60px_-20px_rgba(43,38,32,0.28)]">
                    <img src="/images/LCI-portrait.png" alt="LCI Wellbeing Journal App" class="w-full">
                </div>
            </div>
        </div>
    </section>
@endsection
