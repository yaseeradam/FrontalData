 <div class="bg-card rounded-[40px] bg-white p-4 shadow-xl transition-all duration-500 hover:scale-105">
  <div class="flex items-center mb-2">
    <h3 class="text-sm text-foreground">{{ $transaction->title }}</h3>
  </div>
  <p class="text-muted-foreground text-2xs">{{ $transaction->message }}</p>
   <hr class="border-t-2 border-dotted border-muted my-4">

  <div class="flex justify-between items-center mt-4">
    <span class="text-muted text-xs">@date($transaction->date)</span>
    <a href="{{ $transaction->action_url }}" class="text-primary text-xs">View
    <i class="jannah-arrow-right-3"></i></a>
  </div>
</div>