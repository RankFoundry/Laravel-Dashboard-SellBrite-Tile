<x-dashboard-tile :position="$position">
    <div>
        <ul class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            
            @foreach ($sales as $sale)
            
            <li class="col-span-1 rounded-lg shadow border border-gray-500 bg-gray-100">
                <div class="px-2 py-1 border-b border-gray-500">
                  <h3 class="leading-1 font-medium text-gray-900">
                    {{ $sale['title'] }}
                  </h3>
                  <p class="mt-1 text-xs text-gray-500">
                    {{ $sale['date'] }}
                  </p>
                </div>
             
                    <div class="w-full flex items-center ml-3 p-1">
                      <div class="flex-1 truncate">
                        <div class="flex items-center space-x-3">
                            <p class="text-gray-500 text-xs truncate">GROSS REVENUE</p>
                        </div>
                        <h3 class="mt-1 text-gray-900 text-lg font-medium truncate">{{ $sale['gross'] }}</h3>
                      </div>
                      <div class="flex-1 truncate">
                        <div class="flex items-center space-x-3">
                            <p class="text-gray-500 text-xs truncate">COST</p>
                        </div>
                          <h3 class="mt-1 text-gray-900 text-lg font-medium truncate">{{ $sale['cost'] }}%</h3>
                      </div>
                    </div>            

                    <div class="w-full flex items-center ml-3 p-1">
                      <div class="flex-1 truncate">
                        <div class="flex items-center space-x-3">
                            <p class="text-gray-500 text-xs truncate">NET PROFIT</p>
                        </div>
                        <h3 class="mt-1 text-gray-900 text-lg font-medium truncate">{{ $sale['profit'] }}</h3>
                      </div>
                      <div class="flex-1 truncate">
                        <div class="flex items-center space-x-3">
                            <p class="text-gray-500 text-xs truncate">MARGIN</p>
                        </div>
                          <h3 class="mt-1 text-gray-900 text-lg font-medium truncate">{{ $sale['margin'] }}%</h3>
                      </div>
                    </div>
                
                <div class="border-t border-gray-500">
                  <div class="-mt-px flex divide-x divide-gray-500">
                    <div class="w-0 flex-1 flex">
                      <a href="#" class="relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-xs text-gray-700 font-medium border border-transparent rounded-bl-lg hover:text-gray-500">
                        <!-- Heroicon name: shopping cart -->
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>                        
                        <span class="ml-3">{{ $sale['orders'] }}</span>
                      </a>
                    </div>
                    <div class="-ml-px w-0 flex-1 flex">
                      <a href="#" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-xs text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500">
                        <!-- Heroicon name: price tag -->
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-3">{{ $sale['items'] }}</span>
                      </a>
                    </div>
                  </div>
                </div>
            </li>
            
            @endforeach
            
        </ul>
    </div>
</x-dashboard-tile>