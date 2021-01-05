<x-dashboard-tile :position="$position" refresh-interval="60">
    <ul class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
        
        @foreach ($products as $product)
        
        <li class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200">
            <div class="flex-1 flex flex-col p-2">
              <img class="w-20 h-20 flex-shrink-0 mx-auto bg-black" src="{{ url($product['image']) }}" alt="">
              <h3 class="mt-4 text-gray-900 text-sm font-medium">{{$product['bin']}}   QTY: {{$product['qty']}}</h3>
              <dl class="mt-1 flex-grow flex flex-col justify-between">
                <dt class="sr-only">Title</dt>
                <dd class="text-gray-500 text-sm">{{$product['title']}}</dd>
              </dl>
            </div>
        </li>
                
        @endforeach
        
    </ul>
</x-dashboard-tile>