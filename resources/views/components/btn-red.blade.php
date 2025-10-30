<div>
    @if ($attributes->has('href'))
    <a {{ $attributes->merge(['class' => 'flex gap-3 text-white bg-red-500/85 backdrop-blur-md hover:bg-red-800/85 focus:ring-2 focus:ring-red-300/55 font-medium justify-center items-center rounded-full text-base px-6 py-3.5 me-2 mb-2 dark:bg-red-600/85 dark:hover:bg-red-700/85 focus:outline-none dark:focus:ring-red-800/55 w-full'])}}>
        {{ $slot }}
    </a>
    @else
   <button {{ $attributes->merge(['class' => 'flex gap-3 text-white bg-red-500/85 backdrop-blur-md hover:bg-red-800/85 focus:ring-2 focus:ring-red-300/55 font-medium justify-center items-center rounded-full text-base px-6 py-3.5 me-2 mb-2 dark:bg-red-600/85 dark:hover:bg-red-700/85 focus:outline-none dark:focus:ring-red-800/55 w-full disabled:bg-red-400 disabled:dark:bg-red-400 disabled:cursor-not-allowed'])}}>
        {{ $slot }}
   </button>
   @endif
</div>