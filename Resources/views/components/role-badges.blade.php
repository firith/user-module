<div>
  @foreach($getState() as $badge)
    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
      {{ $badge->name }}
    </div>
  @endforeach
</div>
