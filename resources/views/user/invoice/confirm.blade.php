@extends('user.layout')

@section('title', __('confirm.page_title') . ' | DocuSyns')
@section('page_title', __('confirm.page_title') . ' | DocuSyns')

@section('content')

<div class="bg-gray-100 min-h-screen flex items-center justify-center p-5">
    @if (session('success'))
    <div class="mb-4 p-3 text-green-800 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow-lg rounded-2xl p-8 w-full ">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center select-none">
            {{ __('confirm.heading') }}
        </h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 ">

            @php
            $filePath = asset('storage/' . $allData['file']);
            $extension = strtolower(pathinfo($allData['file'], PATHINFO_EXTENSION));
            @endphp

            <div class="text-center h-100">
                <h2 class="text-lg font-semibold text-gray-700 mb-2 select-none">📄 {{ __('invoice.confirm.original_file') }}</h2>

                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                {{-- Image Preview with Zoom --}}
                <div class="relative  border border-gray-300 rounded-lg shadow-sm inline-block">
                    <img id="zoomImage"
                        src="{{ $filePath }}"
                        alt="Uploaded Image"
                        class="max-w-full h-auto rounded-lg transform transition-transform duration-300 cursor-zoom-in">
                </div>

                <script>
                    const img = document.getElementById('zoomImage');
                    let zoomed = false;

                    img.addEventListener('click', () => {
                        zoomed = !zoomed;
                        img.style.transform = zoomed ? 'scale(2)' : 'scale(1)';
                        img.style.cursor = zoomed ? 'zoom-out' : 'zoom-in';
                    });
                </script>

                @elseif ($extension === 'pdf')
                {{-- PDF Preview --}}
                <iframe src="{{ $filePath }}"
                    class="w-full h-96 rounded-lg border border-gray-300 shadow-sm"
                    frameborder="0">
                </iframe>

                @else
                {{-- Unsupported File --}}
                <p class="text-red-500 mt-4">⚠️ Unsupported file format</p>
                @endif
            </div>

            {{-- Empty Inputs --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2 select-none text-center">📝 {{ __('confirm.heading') }}</h2>
                <div class="space-y-4 overflow-auto ">
                    <div>
                        <label for="reference_commande" class="block text-sm font-medium text-gray-700 mb-1">Reference Commande</label>
                        <input type="text" name="reference_commande" id="reference_commande"
                            class="bg-gray-200 p-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="date_commande" class="block text-sm font-medium text-gray-700 mb-1">Date Commande</label>
                        <input type="text" name="date_commande" id="date_commande"
                            class="bg-gray-200 p-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="nom_fournisseur" class="block text-sm font-medium text-gray-700 mb-1">Nom Fournisseur</label>
                        <input type="text" name="nom_fournisseur" id="nom_fournisseur"
                            class="bg-gray-200 p-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="code_fournisseur" class="block text-sm font-medium text-gray-700 mb-1">Code Fournisseur</label>
                        <input type="text" name="code_fournisseur" id="code_fournisseur"
                            class="bg-gray-200 p-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="commande_par" class="block text-sm font-medium text-gray-700 mb-1">Commandé Par</label>
                        <input type="text" name="commande_par" id="commande_par"
                            class="bg-gray-200 p-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="commande_a" class="block text-sm font-medium text-gray-700 mb-1">Commandé À</label>
                        <input type="text" name="commande_a" id="commande_a"
                            class="bg-gray-200 p-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="montant_ht" class="block text-sm font-medium text-gray-700 mb-1">Montant HT</label>
                        <input type="text" name="montant_ht" id="montant_ht"
                            class="bg-gray-200 p-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="montant_tva" class="block text-sm font-medium text-gray-700 mb-1">Montant TVA</label>
                        <input type="text" name="montant_tva" id="montant_tva"
                            class="bg-gray-200 p-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="montant_ttc" class="block text-sm font-medium text-gray-700 mb-1">Montant TTC</label>
                        <input type="text" name="montant_ttc" id="montant_ttc"
                            class="bg-gray-200 p-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection