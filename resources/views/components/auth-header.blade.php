@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center mb-2">
    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ $title }}</h1>
    <p class="text-white/80 text-sm md:text-base">{{ $description }}</p>
</div>
