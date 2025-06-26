@props(['name'])

<div>
  <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $slot }}</label>
  <input id="{{ $name }}" name="{{ $name }}"
  {{ $attributes->merge(['class'=>'bg-gray-50/85 backdrop-blur-md border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700/85 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 placeholder:text-neutral-400 disabled:opacity-50 disabled:bg-gray-200 dark:disabled:bg-gray-800 dark:disabled:text-gray-500'])}}/>
</div>