<x-page-container class="mt-6">
  <form wire:submit.prevent="submit">
    {{ $this->form }}

    <div class="grid grid-cols-3 gap-4 mt-8">
      <div class="col-span-full lg:col-span-2 px-4 py-4 bg-white rounded-md shadow">
        <x-button type="submit">Save</x-button>
        <x-button-gray color="tertiary" href="{{ route('usermodule.admin.users.index') }}">Back</x-button-gray>
      </div>
    </div>
  </form>

</x-page-container>
