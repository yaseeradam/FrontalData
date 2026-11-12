<div class="grid grid-cols-3 gap-2 mt-4 bg-white p-1 rounded-lg">
  <div onclick="load('{{ route('core.deposit') }}')" class="flex flex-col items-center bg-card p-2 rounded-lg">
    <div class="bg-alt box rounded-xl mb-1">
      <i class="jannah-add box-inner text-primary text-xl"></i>
    </div>
    <span class="text-2xs">Add Money</span>
  </div>
  <div onclick="load('{{ route('core.transfer') }}')" class="flex flex-col items-center bg-card p-2 rounded-lg">
    <div class="bg-alt box rounded-xl mb-1">
      <i class="jannah-send-2 box-inner text-primary text-xl"></i>
    </div>
    <span class="text-2xs">Transfer</span>
  </div>
  <div onclick="load('{{ route('core.earn') }}')" class="flex flex-col items-center bg-card p-2 rounded-lg">
    <div class="bg-alt box rounded-xl mb-1">
      <i class="jannah-coin box-inner text-primary text-xl"></i>
    </div>
    <span class="text-2xs">Earn</span>
   </div>
</div> 