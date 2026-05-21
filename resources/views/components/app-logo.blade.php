@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="EcoFlow" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-[#A3D9A5] dark:text-[#1E3F35]" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="EcoFlow" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-[#A3D9A5] dark:text-[#1E3F35]" />
        </x-slot>
    </flux:brand>
@endif
