<x-app-layout :user="$user">

    <div class="col-span-full md:col-span-3">
        <x-app.back-header title="Transaction History" />
      
            @if ($transactions->isEmpty()) 
            <x-app.none type="Transaction"/>
            @endif
            @foreach ($transactions as $transaction) 
            <x-block.transaction :transaction="$transaction" class="bg-white p-2 rounded-lg "/>
            @endforeach
            {{ $transactions->links() }}
            </div>
  
</x-app-layout>