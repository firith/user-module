<x-nore::page-container class="mt-6">
  <form wire:submit.prevent="submit">
    {{ $this->form }}

    <div class="grid grid-cols-3 gap-4 mt-8">
      <div class="col-span-full lg:col-span-2 px-4 py-4 bg-white rounded-md shadow">
        <x-nore::button type="submit">Save</x-nore::button>
        <x-nore::button-gray color="tertiary" href="{{ route('usermodule.admin.users.index') }}">Back</x-nore::button-gray>
      </div>
    </div>
  </form>

</x-nore::page-container>
