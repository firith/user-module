  <x-page-container class="mt-6 flex flex-col space-y-4">
    <div>
      <x-button :href="route('usermodule.admin.users.create')">Add User</x-button>
    </div>

    {{ $this->table }}
  </x-page-container>
