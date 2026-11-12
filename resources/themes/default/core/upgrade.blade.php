<x-app-layout :drk="true" title="Agent Upgrade" :user="$user">

        <div class="col-span-full md:col-span-3">
        <x-app.back-header title="Agent Upgrade" /> 
          
            <!-- Agent Information Section -->
<div class="bg-white rounded-xl">
    <div class="text-xs font-semibold mb-4">About the @jannah_opt('site_name') Agent Program</div>
    <p class="text-2xs">Agents benefit from lower purchase prices than regular users.</p>
    <p class="text-2xs mt-2">For instance, Agents buy MTN SME data at ₦3 - ₦6 less per GB than regular users, and Glo CG data at ₦2 - ₦8 less per GB.</p>
    <p class="text-2xs mt-2">By referring others to join as Agents, you earn ₦@jannah_opt('agent_upgrade_earning') for each successful upgrade.</p>

    <div class="mt-4">
        <p class="text-2xs font-semibold">Understanding the Agent Program</p>
        <p class="text-2xs mt-2">Each time you make a purchase as an Agent, we apply the standard rate and transfer the commission to your commission wallet.</p>
        <p class="text-2xs mt-2">Additionally, your Referral wallet receives ₦@jannah_opt('agent_upgrade_earning') for every new Agent referral.</p>
    </div>

    <div class="mt-4">
        <p class="text-2xs font-semibold">Steps to Become an Agent</p>
        <p class="text-2xs mt-2">To upgrade to an Agent, a one-time payment of ₦@jannah_opt('agent_upgrade_charges') is required, deducted from your wallet. Once completed, you’ll start earning on every transaction right away.</p>
    </div>

    <div class="mt-4">
        <p class="text-2xs font-semibold">Telecommunications: The New Opportunity</p>
        <p class="text-2xs mt-2">With banks heavily invested, now is your chance to get involved and start earning today.</p>
    </div>
</div>
            <input id="service_type" value="Upgrade To Agent" hidden="" />
            <input name="network" id="network" value="upgrade" hidden="" />
             <input name="number" id="number" value="{{ $user->phone }}" hidden="" />
             
              <input name="amount" id="amount" value="2000" hidden=""/>
              <x-btn.default class="my-4" id="upgrade-b" onclick="toggleCanvas(event)">Upgrade to Agent</x-btn.default>

            <!-- Upgrade Button -->
            <form id="upgrade-f" method="POST">
                @csrf
                <input name="role" id="role" value="agent" hidden />
                <input name="pin" id="pin"  hidden />
                <button class="hidden" id="pay-now"></button>
             </form>
                

        </div>
    
    <script>
        submitForm('upgrade-f', 'pay-now', '{{ route('core.user.upgrade.init') }}');
    </script>
</x-app-layout>
