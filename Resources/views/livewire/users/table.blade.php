  <x-nore::page-container class="mt-6 flex flex-col space-y-4">
    <div>
      <x-nore::button :href="route('usermodule.admin.users.create')">Add User</x-nore::button>
    </div>

    {{ $this->table }}
  </x-nore::page-container>
