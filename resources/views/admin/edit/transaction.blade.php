<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
            <x-admin.form id="edit-transaction-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>User</x-label.main>
                        <x-admin.input.default name="user_id" type="text" required placeholder="Enter User ID" class="cursor-not-allowed pointer-events-none"
                            value="{{ $transaction->user_id }}"  />
                    </div>

                    <div>
                        <x-label.main>Biller</x-label.main>
                        <x-admin.input.default name="biller" type="text" required placeholder="Enter Biller"
                            value="{{ $transaction->biller }}" />
                    </div>

                    <div>
                        <x-label.main>Description</x-label.main>
                        <x-admin.input.default name="description" type="text" required placeholder="Enter Description"
                            value="{{ $transaction->description }}" />
                    </div>

                    <div>
                        <x-label.main>Product</x-label.main>
                        <x-admin.input.select name="product" required>
                            <option value="airtime" {{ $transaction->product === 'airtime' ? 'selected' : '' }}>Airtime</option>
                            <option value="data" {{ $transaction->product === 'data' ? 'selected' : '' }}>Data</option>
                            <option value="cabletv" {{ $transaction->product === 'cabletv' ? 'selected' : '' }}>Cable TV</option>
                            <option value="electricity" {{ $transaction->product === 'electricity' ? 'selected' : '' }}>Electricity</option>
                            <option value="epins" {{ $transaction->product === 'epins' ? 'selected' : '' }}>Epins</option>
                        </x-admin.input.select>
                    </div>

                    <div>
                        <x-label.main>Session ID</x-label.main>
                        <x-admin.input.default name="session_id" class="cursor-not-allowed pointer-events-none" type="text" required placeholder="Enter Session ID"
                            value="{{ $transaction->session_id }}" />
                    </div>

                    <div>
                        <x-label.main>Amount (₦)</x-label.main>
                        <x-admin.input.default name="amount" type="number" required placeholder="Enter Amount"
                            value="{{ $transaction->amount }}" />
                    </div>

                    <div>
                        <x-label.main>Number</x-label.main>
                        <x-admin.input.default name="number" type="text" required placeholder="Enter Number"
                            value="{{ $transaction->number }}" />
                    </div>
                    </div>
                    </div>
                     <div class="bg-white rounded shadow p-6 overflow-x-auto">
                          <div class="space-y-4">
                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-admin.input.select name="status" required>
                            <option value="successful" {{ $transaction->status === 'successful' ? 'selected' : '' }}>Successful</option>
                            <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $transaction->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        </x-admin.input.select>
                    </div>
                    
                    <a href="{{ route('admin.user', ['id' => $transaction->user_id]) }}" class="inline-flex items-center gap-x-2 text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800">Refund Transaction</a>


                    <div>
                        <x-label.main>Old Balance (₦)</x-label.main>
                        <x-admin.input.default name="old_balance" type="number" required placeholder="Enter Old Balance"
                            value="{{ $transaction->old_balance }}" />
                    </div>

                    <div>
                        <x-label.main>New Balance (₦)</x-label.main>
                        <x-admin.input.default name="new_balance" type="number" required placeholder="Enter New Balance"
                            value="{{ $transaction->new_balance }}" />
                    </div>

                    <div>
                        <x-label.main>Profit (₦)</x-label.main>
                        <x-admin.input.default name="profits" type="number" required placeholder="Enter Profit"
                            value="{{ $transaction->profits }}" />
                    </div>

                    <div>
                        <x-label.main>Date</x-label.main>
                        <x-admin.input.default name="date" type="datetime-local" required placeholder="Enter date"
                            value="{{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d\TH:i') }}" />
                    </div>
                
                <div class="!mt-8 mb-12">
                    <x-admin.btn.default type="button" id="edit-transaction-button">
                        Save Settings
                    </x-admin.btn.default>
                </div>
                </div>
                </div>
            </x-admin.form>
        
        <script>
            submitForm('edit-transaction-form', 'edit-transaction-button', '{{ route('admin.update.transaction', ['id' => $transaction->id]) }}');
        </script>
      
      </div>
  
    </div>
        </div>
</x-admin-layout> 