<footer class="border-t border-hairline/60 bg-paper/65 backdrop-blur-lg">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid gap-8 sm:grid-cols-2">
            <div>
                <div class="text-sm font-semibold text-ink">LCI Wellbeing</div>
                <div class="mt-2 max-w-md text-sm leading-6 text-ink-soft">
                    Calm, grounded support for individuals and organisations. Trauma-informed therapy and wellbeing services designed for clarity, steadiness, and meaningful change.
                </div>
                <div class="mt-4 font-script italic text-lg text-gold">Come home to yourself.</div>
            </div>
            <div class="sm:justify-self-end">
                <div class="text-sm font-semibold text-ink">Explore</div>
                <div class="mt-3 grid gap-2 text-sm">
                    <a class="text-ink-soft transition hover:text-ink" href="{{ route('private-therapy') }}">Private Therapy</a>
                    <a class="text-ink-soft transition hover:text-ink" href="{{ route('corporate-services') }}">Corporate Services</a>
                    <a class="text-ink-soft transition hover:text-ink" href="{{ route('app') }}">LCI Wellbeing Journal</a>
                    <a class="text-ink-soft transition hover:text-ink" href="{{ route('contact') }}">Contact</a>
                </div>
            </div>
        </div>

        <div class="mt-10 flex flex-col gap-2 border-t border-hairline pt-6 text-xs text-ink-soft sm:flex-row sm:items-center sm:justify-between">
            <div>&copy; {{ date('Y') }} LCI Wellbeing. All rights reserved.</div>
            <div class="flex items-center gap-4">
                <a class="transition hover:text-ink" href="/privacy-policy.html">Privacy</a>
                <a class="transition hover:text-ink" href="/account-delete.html">Account Delete</a>
            </div>
        </div>
    </div>
</footer>

