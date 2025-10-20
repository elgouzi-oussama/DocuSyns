<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Create Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    @if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 4000)"
        class="mb-4 rounded-lg bg-red-100 border border-red-300 text-green-800 px-4 py-3 flex items-center justify-between"
        role="alert">
        <span class="font-medium">{{ session('error') }}</span>
        <button
            type="button"
            class="text-green-600 hover:text-green-800 font-bold text-xl leading-none ml-4"
            @click="show = false">
            ×
        </button>
    </div>
    @endif
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Create Invoice</h1>
        <a href="{{ route('invoice.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mb-4 inline-block">Back to Invoices</a>

        <form action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
            @csrf


            <div class="mb-4">
                <label for="file" class="block text-sm font-medium">Upload File (Image or PDF)</label>
                <input type="file" name="file" id="file" class="w-full p-2 border rounded" accept="image/*,application/pdf">
                @error('file')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Submit</button>
        </form>
    </div>
</body>

</html>