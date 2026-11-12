<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
            <x-admin.form id="fund-user-form ">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x.label.main class="text-sm font-semibold">Amount</x.label.main>
                        <x-admin.input.default name="amount" type="number" required placeholder="Enter Amount"/>
                    </div>
                    
                    <div>
                        <x.label.main class="text-sm font-semibold">Narration</x.label.main>
                        <x-admin.input.default name="description" type="text" required placeholder="Enter Narration"/>
                    </div>
                    <div>
                        <x.label.main class="text-sm font-semibold">Select Type</x.label.main>
                        <x-admin.input.select name="type">
                            <option value="debit"> Debit</option>
                            <option value="credit"> Credit</option>
                        </x-admin.input.select>
                        
                    </div>
                        <input name="id" value="{{ $user->id }}" class="hidden"></input>
                        <div class="!mt-8 mb-12">
                    <x-admin.btn.default type="button" id="fund-user-button">
                         Transacts Now
                    </x-admin.btn.default>
                    <script>
                         submitForm('fund-user-form', 'fund-user-button', '{{ route('admin.fund.user', $user->id) }}');
                    </script>
                </div>
                </div>
            </x-admin.form>
            
            </div>
            
             <div class="bg-white rounded shadow p-6 overflow-x-auto">
                 
            <div class="text-primary text-sm font-semibold">Manage User Data</div>
            <x-admin.form id="edit-user-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Username</x-label.main>
                        <x-admin.input.default name="username" type="text" required class="cursor-not-allowed pointer-events-none" value="{{ $user->username }}" />
                    </div>

                    <div>
                        <x-label.main>Email</x-label.main>
                        <x-admin.input.default name="email" type="email" required value="{{ $user->email }}" />
                    </div>

                    <div>
                        <x-label.main>Phone</x-label.main>
                        <x-admin.input.default name="phone" type="text" required value="{{ $user->phone }}" />
                    </div>

                    <div>
                        <x-label.main>First Name</x-label.main>
                        <x-admin.input.default name="first_name" type="text" value="{{ $user->first_name }}" />
                    </div>

                    <div>
                        <x-label.main>Last Name</x-label.main>
                        <x-admin.input.default name="last_name" type="text" value="{{ $user->last_name }}" />
                    </div>

                    <div>
                        <x-label.main>Role</x-label.main>
                        <x-admin.input.select name="role" required>
                            <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="reseller" {{ $user->role === 'reseller' ? 'selected' : '' }}>Reseller</option>
                            <option value="agent" {{ $user->role === 'agent' ? 'selected' : '' }}>Agent</option>
                        </x-admin.input.select>
                    </div>

                    <div>
                        <x-label.main>Wallet Balance</x-label.main>
                        <x-admin.input.default name="wallet_balance" type="text" class="cursor-not-allowed pointer-events-none" value="₦{{ number_format($user->wallet_balance, 2) }}" />
                    </div>

                    <div>
                        <x-label.main>Pending Balance</x-label.main>
                        <x-admin.input.default name="pending_balance" type="text" disabled="" value="₦{{ number_format($user->pending_balance, 2) }}" />
                    </div>

                    <div>
                        <x-label.main>Referral Balance</x-label.main>
                        <x-admin.input.default name="referral_balance" type="text" disabled="" value="₦{{ number_format($user->referral_balance, 2) }}" />
                    </div>

                    <div>
                        <x-label.main>Cashback Balance</x-label.main>
                        <x-admin.input.default name="cashback_balance" type="text" disabled="" value="₦{{ number_format($user->cashback_balance, 2) }}" />
                    </div>

                    <div>
                        <x-label.main>PIN</x-label.main>
                        <x-admin.input.default name="pin" type="text" value="{{ $user->pin }}" />
                    </div>
                </div>
                <div class="!mt-8 mb-12">
                    <x-admin.btn.default type="button" id="edit-user-button">
                        Save Changes
                    </x-admin.btn.default>
                </div>
                </div>
            </x-admin.form>
       
        <script>
            submitForm('edit-user-form', 'edit-user-button', '{{ route('admin.update.user', $user->id) }}');
        </script>
       
      </div>
  
    </div>
        </div>
</x-admin-layout> 