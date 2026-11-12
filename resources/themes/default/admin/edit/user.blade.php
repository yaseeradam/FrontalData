<x-admin-layout :drk="true" title="Edit User">
    <x-admin.frame>
        <main class="min-h-screen bg-background space-y-6 text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
            <x-admin.title>Edit User</x-admin.title>
            <div class="text-primary text-sm font-semibold">Add Transaction</div>
            <x-admin.form id="fund-user-form ">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x.label.main class="text-sm font-semibold">Amount</x.label.main>
                        <x-input.default name="amount" type="number" required placeholder="Enter Amount"/>
                    </div>
                    
                    <div>
                        <x.label.main class="text-sm font-semibold">Narration</x.label.main>
                        <x-input.default name="description" type="text" required placeholder="Enter Narration"/>
                    </div>
                    <div>
                        <x.label.main class="text-sm font-semibold">Select Type</x.label.main>
                        <x-input.select name="type">
                            <option value="debit"> Debit</option>
                            <option value="credit"> Credit</option>
                        </x-input.select>
                        
                    </div>
                        <input name="id" value="{{ $user->id }}" hidden />
                        <div class="!mt-8 mb-12">
                    <x-btn.default type="button" id="fund-user-button">
                         Transacts Now
                    </x-btn.default>
                    <script>
                         submitForm('fund-user-form', 'fund-user-button', '{{ route('admin.fund.user', $user->id) }}');
                    </script>
                </div>
                </div>
            </x-admin.form>
            <div class="text-primary text-sm font-semibold">Manage User Data</div>
            <x-admin.form id="edit-user-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Username</x-label.main>
                        <x-input.default name="username" type="text" required class="cursor-not-allowed pointer-events-none" value="{{ $user->username }}" />
                    </div>

                    <div>
                        <x-label.main>Email</x-label.main>
                        <x-input.default name="email" type="email" required value="{{ $user->email }}" />
                    </div>

                    <div>
                        <x-label.main>Phone</x-label.main>
                        <x-input.default name="phone" type="text" required value="{{ $user->phone }}" />
                    </div>

                    <div>
                        <x-label.main>First Name</x-label.main>
                        <x-input.default name="first_name" type="text" value="{{ $user->first_name }}" />
                    </div>

                    <div>
                        <x-label.main>Last Name</x-label.main>
                        <x-input.default name="last_name" type="text" value="{{ $user->last_name }}" />
                    </div>

                    <div>
                        <x-label.main>Role</x-label.main>
                        <x-input.select name="role" required>
                            <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="reseller" {{ $user->role === 'reseller' ? 'selected' : '' }}>Reseller</option>
                            <option value="agent" {{ $user->role === 'agent' ? 'selected' : '' }}>Agent</option>
                        </x-input.select>
                    </div>

                    <div>
                        <x-label.main>Wallet Balance</x-label.main>
                        <x-input.default name="wallet_balance" type="text" class="cursor-not-allowed pointer-events-none" value="₦{{ number_format($user->wallet_balance, 2) }}" />
                    </div>

                    <div>
                        <x-label.main>Pending Balance</x-label.main>
                        <x-input.default name="pending_balance" type="text" disabled="" value="₦{{ number_format($user->pending_balance, 2) }}" />
                    </div>

                    <div>
                        <x-label.main>Referral Balance</x-label.main>
                        <x-input.default name="referral_balance" type="text" disabled="" value="₦{{ number_format($user->referral_balance, 2) }}" />
                    </div>

                    <div>
                        <x-label.main>Cashback Balance</x-label.main>
                        <x-input.default name="cashback_balance" type="text" disabled="" value="₦{{ number_format($user->cashback_balance, 2) }}" />
                    </div>

                    <div>
                        <x-label.main>PIN</x-label.main>
                        <x-input.default name="pin" type="text" value="{{ $user->pin }}" />
                    </div>
                </div>
                <div class="!mt-8 mb-12">
                    <x-btn.default type="button" id="edit-user-button">
                        Save Changes
                    </x-btn.default>
                </div>
            </x-admin.form>
        </main>
        <script>
            submitForm('edit-user-form', 'edit-user-button', '{{ route('admin.update.user', $user->id) }}');
        </script>
    </x-admin.frame>
</x-admin-layout>