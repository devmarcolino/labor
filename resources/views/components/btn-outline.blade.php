<div>
    @if ($attributes->has('href'))
    <a {{ $attributes->merge(['class'=>'flex gap-3 items-center justify-center text-sky-600 bg-white/75 border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-full text-base px-6 py-3.5 me-2 mb-2 dark:bg-gray-800/75 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 w-full disabled:opacity-50 disabled:cursor-not-allowed']) }}>
        {{ $slot }}
    </a>
    @else
    <button {{ $attributes->merge(['class'=>'flex gap-3 items-center justify-center text-sky-600 bg-white/75 border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-full text-base px-6 py-3.5 me-2 mb-2 dark:bg-gray-800/75 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 w-full disabled:opacity-50 disabled:cursor-not-allowed']) }}>
        {{ $slot }}
    </button>
    @endif
</div>