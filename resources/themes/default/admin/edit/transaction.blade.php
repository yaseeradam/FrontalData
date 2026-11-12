<x-admin-layout :drk="true" title="Edit Transaction">
    <x-admin.frame>
        <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
            <x-admin.title>Edit Transaction</x-admin.title>
            <x-admin.form id="edit-transaction-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>User</x-label.main>
                        <x-input.default name="user_id" type="text" required placeholder="Enter User ID" class="cursor-not-allowed pointer-events-none"
                            value="{{ $transaction->user_id }}"  />
                    </div>

                    <div>
                        <x-label.main>Biller</x-label.main>
                        <x-input.default name="biller" type="text" required placeholder="Enter Biller"
                            value="{{ $transaction->biller }}" />
                    </div>

                    <div>
                        <x-label.main>Description</x-label.main>
                        <x-input.default name="description" type="text" required placeholder="Enter Description"
                            value="{{ $transaction->description }}" />
                    </div>

                    <div>
                        <x-label.main>Product</x-label.main>
                        <x-input.select name="product" required>
                            <option value="airtime" {{ $transaction->product === 'airtime' ? 'selected' : '' }}>Airtime</option>
                            <option value="data" {{ $transaction->product === 'data' ? 'selected' : '' }}>Data</option>
                            <option value="cabletv" {{ $transaction->product === 'cabletv' ? 'selected' : '' }}>Cable TV</option>
                            <option value="electricity" {{ $transaction->product === 'electricity' ? 'selected' : '' }}>Electricity</option>
                            <option value="epins" {{ $transaction->product === 'epins' ? 'selected' : '' }}>Epins</option>
                        </x-input.select>
                    </div>

                    <div>
                        <x-label.main>Session ID</x-label.main>
                        <x-input.default name="session_id" class="cursor-not-allowed pointer-events-none" type="text" required placeholder="Enter Session ID"
                            value="{{ $transaction->session_id }}" />
                    </div>

                    <div>
                        <x-label.main>Amount (₦)</x-label.main>
                        <x-input.default name="amount" type="number" required placeholder="Enter Amount"
                            value="{{ $transaction->amount }}" />
                    </div>

                    <div>
                        <x-label.main>Number</x-label.main>
                        <x-input.default name="number" type="text" required placeholder="Enter Number"
                            value="{{ $transaction->number }}" />
                    </div>

                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-input.select name="status" required>
                            <option value="successful_debit">Successful & Debit User</option>
                            <option value="successful" {{ $transaction->status === 'successful' ? 'selected' : '' }}>Successful</option>
                            <option value="failed_credit">Failed && Credit User </option>
                            <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $transaction->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        </x-input.select>
                    </div>

                    <div>
                        <x-label.main>Old Balance (₦)</x-label.main>
                        <x-input.default name="old_balance" type="number" required placeholder="Enter Old Balance"
                            value="{{ $transaction->old_balance }}" />
                    </div>

                    <div>
                        <x-label.main>New Balance (₦)</x-label.main>
                        <x-input.default name="new_balance" type="number" required placeholder="Enter New Balance"
                            value="{{ $transaction->new_balance }}" />
                    </div>

                    <div>
                        <x-label.main>Profit (₦)</x-label.main>
                        <x-input.default name="profits" type="number" required placeholder="Enter Profit"
                            value="{{ $transaction->profits }}" />
                    </div>

                    <div>
                        <x-label.main>Date</x-label.main>
                        <x-input.default name="date" type="datetime-local" required placeholder="Enter date"
                            value="{{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d\TH:i') }}" />
                    </div>
                </div>
                <div class="!mt-8 mb-12">
                    <x-btn.default type="button" id="edit-transaction-button">
                        Save Settings
                    </x-btn.default>
                </div>
            </x-admin.form>
        </main>
        <script>
            submitForm('edit-transaction-form', 'edit-transaction-button', '{{ route('admin.update.transaction', ['id' => $transaction->id]) }}');
        </script>
    </x-admin.frame>
</x-admin-layout>