@props(['color' => 'text-sky-600'])

<div {{ $attributes->merge(['class' => "animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent $color rounded-full"]) }} 
     role="status" aria-label="loading">
    <span class="sr-only">Carregando...</span>
</div>