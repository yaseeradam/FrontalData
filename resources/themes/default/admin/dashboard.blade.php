<x-admin-layout :drk="true" title="Dashboard">
  <x-admin.frame>
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
      <x-admin.header />
      
      <div class="swiper-container">
          <div class="swiper-wrapper">
        @foreach($walletBalances as $wallet)
            <x-block.slider name="{{ $wallet['name'] }}" bal="{{ $wallet['balance'] }}" />
        @endforeach
        </div>
    </div>
      <div class="p-2 mt-3 font-bold text-lg">Users Analytics</div>
      <ul class="flex flex-col">
        <x-block.list name="Total Users" :tp="true" :amt="$user_no" />
        <x-block.list name="Total Agents" :tp="true" :amt="$t_agent" />
        <x-block.list name="Total Resellers" :tp="true" :amt="$t_reseller" />
        <x-block.list name="Total Customers" :tp="true" :amt="$t_customer" />
         <x-block.list name="Total User Balance" :tp="true" :amt="'₦' . $t_user_bal"/>
      </ul>

      <!-- Transactions Analytics -->
      <div class="p-2 mt-3 font-bold text-lg">Transactions Analytics</div>
      <ul class="flex flex-col">
        <x-block.list name="Total Transactions" :tp="true" :amt="$transaction_no" />
        <x-block.list name="Total Successful" :tp="true" :amt="$t_successful" status="success" />
        <x-block.list name="Total Pending" :tp="true" :amt="$t_pending" status="pending" />
        <x-block.list name="Total Failed" :tp="true" :amt="$t_failed" status="failed" />
        <x-block.list name="Total Processing" :tp="true" :amt="$t_processing" status="processing" />
      </ul>

      <script>
        var swiper = new Swiper('.swiper-container', {
          slidesPerView: 2,
          spaceBetween: 16,
          breakpoints: {
            768: {
              slidesPerView: 4,
            }
          },
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
        });
      </script>
    </main>
  </x-admin.frame>
</x-admin-layout>