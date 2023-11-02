<div>
    <div class="w-full mb-10 text-lg font-bold">Generated/Manual Invoice</label>
        <input wire:model="search" type="number" id="invoice" name="invoice" autocomplete="invoice" required
            class="w-96 flex-auto rounded-md border-0 bg-white/5 px-3.5 py-2  shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 auto"
            placeholder="Enter your Invoice">
    </div>
    <!-- info deatials -->
    @isset($booking)
        <div>
            <ul role="list" class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                <li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
                    <div class="flex w-full items-center justify-between space-x-6 p-6">
                        <div class="flex-1 truncate">
                            <div class="flex items-center space-x-3">
                                <h3 class="truncate text-sm font-medium text-gray-900">{{ $booking->sender->full_name }}
                                </h3>
                                <span
                                    class="inline-flex flex-shrink-0 items-center rounded-full bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Sender</span>
                            </div>
                            <p class="mt-1 truncate text-sm text-gray-500">{{ $booking->senderaddress->address }}</p>
                            <p class="mt-1 truncate text-sm text-gray-500">{{ $booking->senderaddress->citycan->name }}
                                {{ $booking->senderaddress->provincecan->name }}
                                {{ $booking->senderaddress->postal_code }}</p>

                            </p>

                        </div>

                    </div>
                    <div>
                    </div>
                </li>
                <li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
                    <div class="flex w-full items-center justify-between space-x-6 p-6">
                        <div class="flex-1 truncate">
                            <div class="flex items-center space-x-3">
                                <h3 class="truncate text-sm font-medium text-gray-900">{{ $booking->receiver->full_name }}
                                </h3>
                                <span
                                    class="inline-flex flex-shrink-0 items-center rounded-full bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Receiver</span>
                            </div>
                            <p class="mt-1 truncate text-sm text-gray-500">{{ $booking->receiveraddress->address }}</p>
                            <p class="mt-1 truncate text-sm text-gray-500">
                                {{ $booking->receiveraddress->barangayphil->name }}
                                {{ $booking->receiveraddress->cityphil->name }}
                                {{ $booking->receiveraddress->provincephil->name }}</p>
                        </div>

                    </div>
                    <div>
                    </div>
                </li>
            </ul>
        </div>
    @endisset
    <!--- end info deatials -->
    @isset($currentstatus)
    <div class="py-4 mt-2  bg-white  shadow rounded-lg">
        
            <div class="text-lg"><span class="text-xl font-bold text-gray-900 px-4">Batch Number: </span><span
                    class="text-blue-900 2xl  pl-4"> {{ $booking->batch->batchno }}-{{$booking->batch->batch_year}}</span></div>
       
    </div>
    @endisset
    @isset($currentstatus)
    <div class="py-4 mt-2  bg-white  shadow rounded-lg">
        
            <div class="text-lg"><span class="text-xl font-bold text-gray-900 px-4">Current Status: </span><span
                    class="text-blue-900 2xl  pl-4"> {{ $currentstatus->trackstatus->description }}</span></div>
       
    </div>
    @endisset
    @isset($currentstatus)
        <div class="bg-white py-4 shadow rounded-lg mt-2">
            <div class="flow-root mx-10">
                <ul role="list" class="-mb-8">
                    @foreach ($invoicestatus as $invstatus)
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span
                                            class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor"
                                                aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                    clip-rule="evenodd" />
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
                                            <p class="font-medium text-gray-900">
                                                {{ $invstatus->trackstatus->description }}</a></p>
                                        </div>
                                        <div class="whitespace-nowrap text-left text-sm text-gray-900">
                                            <time
                                                datetime="2020-10-04">{{ $invstatus->remarks }}{{ $invstatus->booking_invoice }}</time>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-sm text-gray-900">
                                            <time datetime="2020-10-04">{{ $invstatus->date_update }}</time>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endisset
</div>
