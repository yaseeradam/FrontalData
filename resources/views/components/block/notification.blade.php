 <div class="bg-card rounded-lg bg-gray-100 p-4">
  <div class="flex items-center mb-2">
    <div class="w-5 h-5 flex items-center justify-center rounded-full">
      <img src="https://placehold.co/40x40" class="rounded-full">
    </div>
    <h3 class="ml-2 text-sm text-foreground">{{ $transaction->title }}</h3>
  </div>
  <p class="text-muted-foreground text-2xs">{{ $transaction->message }}</p>
   <hr class="border-t-2 border-dotted border-muted my-4">

  <div class="flex justify-between items-center mt-4">
    <span class="text-muted text-xs">@date($transaction->date)</span>
    <a href="{{ $transaction->action_url }}" class="text-primary text-xs">View
    <i class="jannah-arrow-right-3"></i></a>
  </div>
</div>