@props(['user'])

<span
    {{ $attributes->class(['relative inline-flex shrink-0 items-center justify-center overflow-hidden']) }}
    data-user-avatar>
    @if ($user->profile_photo_url)
    <img
        src="{{ $user->profile_photo_url }}"
        alt="Foto profil {{ $user->name }}"
        class="absolute inset-0 size-full object-cover"
        data-user-avatar-image />
    @endif
    <span
        @class(['flex size-full items-center justify-center', 'hidden' => $user->profile_photo_url])
        data-user-avatar-fallback>
        {{ $user->initials }}
    </span>
</span>
