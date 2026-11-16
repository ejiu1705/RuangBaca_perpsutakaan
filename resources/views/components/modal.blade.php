@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
    'label' => 'Dialog konfirmasi',
])

@php
    $widthClass = [
        'sm' => 'rb-modal-sm',
        'md' => 'rb-modal-md',
        'lg' => 'rb-modal-lg',
        'xl' => 'rb-modal-xl',
        '2xl' => 'rb-modal-2xl',
    ][$maxWidth] ?? 'rb-modal-2xl';
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            const selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
            return [...$el.querySelectorAll(selector)].filter(element => ! element.hasAttribute('disabled'));
        },
        firstFocusable() { return this.focusables()[0]; },
        lastFocusable() { return this.focusables().slice(-1)[0]; },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable(); },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable(); },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1); },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1; },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('rb-modal-open');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable()?.focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('rb-modal-open');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey ? prevFocusable()?.focus() : nextFocusable()?.focus()"
    x-show="show"
    x-cloak
    class="rb-modal-root"
    style="display: {{ $show ? 'grid' : 'none' }};"
    aria-modal="true"
    role="dialog"
    aria-label="{{ $label }}"
>
    <button type="button" class="rb-modal-backdrop" x-show="show" x-on:click="show = false" aria-label="Tutup dialog"></button>
    <div
        x-show="show"
        class="rb-modal-panel {{ $widthClass }}"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-3 scale-95"
    >
        {{ $slot }}
    </div>
</div>
