<div>
    @if ($attributes->has('href'))
    <a {{ $attributes->merge(['class' => 'flex items-center gap-2 justify-center text-white bg-sky-600/85 backdrop-blur-md hover:bg-sky-800/85 focus:ring-2 focus:ring-sky-300/55 font-medium rounded-full text-base px-6 py-3.5 me-2 mb-2 dark:bg-sky-600/85 dark:hover:bg-sky-700/85 focus:outline-none dark:focus:ring-sky-800/55 w-full disabled:opacity-50 disabled:cursor-not-allowed'])}}>
        {{ $slot }}
    </a>
    @else
   <button {{ $attributes->merge(['class' => 'text-white gap-2 flex items-center justify-center bg-sky-600/85 backdrop-blur-md hover:bg-sky-800/85 focus:ring-2 focus:ring-sky-300/55 font-medium rounded-full text-base px-6 py-3.5 me-2 mb-2 dark:bg-sky-600/85 dark:hover:bg-sky-700/85 focus:outline-none dark:focus:ring-sky-800/55 w-full disabled:opacity-50 disabled:cursor-not-allowed'])}}>
        {{ $slot }}
   </button>
   @endif
</div>