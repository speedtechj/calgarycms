<div>
    <div class="w-full mb-10 text-lg font-bold">Generated/Manual Invoice</label>
        <input wire:model="search"  type="text"  required
            class="w-96 flex-auto rounded-md border-0 bg-white/5 px-3.5 py-2  shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
            placeholder="Enter your Invoice">
    </div>

<div class="py-4 mx-10 mb-4 ">
    @isset($currentstatus)
        <div class="text-lg"><span class="text-xl font-bold">Current Status:  </span><span class="text-orange-500 2xl underline pl-4"> {{ $currentstatus->trackstatus->description }}</span></div>
          
    
    @endisset
</div>
<div class="flow-root mx-10">
    <ul role="list" class="-mb-8">
        @foreach ($invoicestatus as $invstatus)
            <li>
                <div class="relative pb-8">
                    <div class="relative flex space-x-3">
                        <div>
                            <span
                                class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg> --}}

                            </span>
                        </div>
                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                            <div>
                                <p class="font-medium text-amber-600">{{ $invstatus->trackstatus->description }}</a></p>
                            </div>
                            <div class="whitespace-nowrap text-left text-sm text-amber-600">
                                <time datetime="2020-10-04">{{ $invstatus->remarks }}{{ $invstatus->booking_invoice }}</time>
                            </div>
                            <div class="whitespace-nowrap text-right text-sm text-amber-600">
                                <time datetime="2020-10-04">{{ $invstatus->date_update }}</time>
                            </div>

                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>

{{-- <div class="w-full bg-red">Gerated/Manual Invoice</label>
        <input wire:model="search"id="email-address" name="email" type="email" autocomplete="email" required class="min-w-0 max-w-full flex-auto rounded-md border-0 bg-white/5 px-3.5 py-2  shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" placeholder="Enter your email">
       
      </div>
      @isset($currentstatus)
      <p>Current Status: {{$currentstatus->trackstatus->description}}-
       Date: {{$currentstatus->date_update}}
      </p>
  @endisset
     <div class="py-10">
     
             </div> --}}
</div>
