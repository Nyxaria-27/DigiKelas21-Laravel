@if (session('success'))
<div class="p-3 bg-green-50 border border-green-200 text-green-800 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded mb-4">
    {{ session('error') }}
</div>
@endif

@if ($errors->any())
<div class="p-3 bg-yellow-50 border border-yellow-200 text-yellow-900 rounded mb-4">
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $err)
        <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif