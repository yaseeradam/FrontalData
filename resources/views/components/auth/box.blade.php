<div {{ $attributes->merge(['class' => 'flex flex-col justify-center items-center bg-[#f1f1f1] h-[100vh]' ]) }}> 
 <div class="mx-auto flex w-full flex-col justify-center px-5 pt-0 md:h-[unset] md:max-w-[33%] lg:h-[100vh] min-h-[100vh] lg:max-w-[33%] lg:px-10">
  {{ $slot }}   
 </div>

</div>