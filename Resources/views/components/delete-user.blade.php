<div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
  <div>
    <div class="text-sm text-gray-500">
      <div class="text-sm font-medium leading-4 text-gray-700 mb-8">
        Delete User
      </div>

      <p>
        Once you delete this user, you will lose all data associated with it.
      </p>

      <div class="mt-5">
        <x-nore::button-danger
          type="button"
          color="secondary"
          wire:click="$set('showModal', true)"
        >
          Delete user
        </x-nore::button-danger>
      </div>
    </div>

    <x-nore::dialog-modal wire:model="showModal">
      <x-slot name="title"><span x-text="state.name"></span></x-slot>
      <x-slot name="content">
        Do you want to delete this user?
      </x-slot>
      <x-slot name="footer">
        <x-nore::button-gray color="tertiary" class="hover:bg-gray-200" type="button" wire:click="$set('showModal', false)">Cancel</x-nore::button-gray>
        <x-nore::button-danger type="button" wire:click="deleteUser">Delete User</x-nore::button-danger>
      </x-slot>
    </x-nore::dialog-modal>
  </div>
</div>
