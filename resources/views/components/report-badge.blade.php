@props(['type', 'value', 'label'])

@php
    $color = match ($type) {
        'status' => match ($value) {
            'completed' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'in_process' => 'border-blue-200 bg-blue-50 text-blue-700',
            'approved' => 'border-sky-200 bg-sky-50 text-sky-700',
            'rejected' => 'border-rose-200 bg-rose-50 text-rose-700',
            'reported', 'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
            default => 'border-neutral-200 bg-neutral-50 text-neutral-700',
        },
        'priority' => match ($value) {
            'low' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'medium' => 'border-amber-200 bg-amber-50 text-amber-700',
            'high', 'critical' => 'border-rose-200 bg-rose-50 text-rose-700',
            default => 'border-neutral-200 bg-neutral-50 text-neutral-700',
        },
        'urgency' => match ($value) {
            'rendah' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'sedang' => 'border-amber-200 bg-amber-50 text-amber-700',
            'tinggi' => 'border-rose-200 bg-rose-50 text-rose-700',
            default => 'border-neutral-200 bg-neutral-50 text-neutral-700',
        },
        default => 'border-neutral-200 bg-neutral-50 text-neutral-700',
    };
@endphp

<span {{ $attributes->class(["inline-flex items-center rounded-md border px-2.5 py-1 text-xs font-semibold {$color}"]) }}>
    {{ $label }}
</span>
