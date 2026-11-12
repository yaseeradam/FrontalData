@php
    $pop = jannah_opt('show_popup');
    
@endphp
     
<x-app-layout :drk="true" title="Dashboard" :user="$user">
    
    <x-app.pop>{{ $msg }}</x-app.pop>
    @if ($pop != "disabled")
         <script>
             showPopup()
         </script>
     @endif
    <x-app.stats :balance="$user->wallet_balance" :transactions="$transactions"></x-app.stats>
            <x-app.notice></x-app.notice>
            <x-app.services></x-app.services>
            <x-app.md :sales="$sales"></x-app.md>
            

</x-app-layout>
