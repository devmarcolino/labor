@props(['name'])

<div class="relative">
              @if (isset($icon))
                  <div class="absolute inset-y-0 end-0 flex items-center pe-3.5 pointer-events-none">
                    {{ $icon }}
                  </div>       
              @endif

              <input 
              id="{{ $name }}" 
              name="{{ $name }}" 
              {{ $attributes->merge(['class'=>'peer p-4 block w-full bg-gray-50 border-gray-200 rounded-lg sm:text-sm placeholder:text-transparent focus:border-sky-500 focus:ring-sky-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2',
              'ps-10' => isset($icon),
               ])
              }}> 
              <label for="{{ $name }}"
              class="absolute top-0 start-0 p-4 h-full sm:text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent origin-[0_0] text-gray-600  dark:text-gray-100 peer-disabled:opacity-50 peer-disabled:pointer-events-none
              peer-focus:scale-90
              peer-focus:translate-x-0.5
              peer-focus:-translate-y-1.5
              peer-focus:text-gray-500 dark:peer-focus:text-gray-300
              peer-[:not(:placeholder-shown)]:scale-90
              peer-[:not(:placeholder-shown)]:translate-x-0.5
              peer-[:not(:placeholder-shown)]:-translate-y-1.5
              peer-[:not(:placeholder-shown)]:text-gray-500 dark:peer-[:not(:placeholder-shown)]:text-gray-300 dark:text-gray-300">

              {{ $slot }}
            </label>
</div>