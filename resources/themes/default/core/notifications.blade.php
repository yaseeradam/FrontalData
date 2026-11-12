<x-app-layout :user="$user">

    
            <div class="col-span-full md:col-span-3">
        <x-app.back-header title="Notifications" />
            @if ($notifications->isEmpty()) 
            <x-app.none type="Notification"/>
            @endif
            @foreach ($notifications as $notification) 
            <x-block.notification :transaction="$notification" class="bg-white p-2 rounded-lg "/>
            @endforeach
            </div>
       
  
</x-app-layout>