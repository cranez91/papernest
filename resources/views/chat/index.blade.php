@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 h-screen bg-gray-300 text-gray-800">
        <div class="max-w-xl mx-auto mt-10">
            <h2 class="text-2xl font-bold mb-4 text-center">
                🤖 Chat para agregar productos
            </h2>
            <div id="chat-box"
                class="border p-4 h-64 overflow-y-scroll bg-gray-100">
            </div>
            <form id="chat-form"
                class="mt-4">
                <input type="text"
                    id="chat-input"
                    class="w-full border p-2"
                    placeholder="Escribe tu mensaje..." />
                <button type="submit"
                        class="mt-2 px-4 py-2 bg-blue-500 text-white">
                    Enviar
                </button>
            </form>
        </div>
    </div>
    <script>
        const form = document.getElementById('chat-form');
        const input = document.getElementById('chat-input');
        const box = document.getElementById('chat-box');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = input.value;
            if (!msg) return;

            box.innerHTML += `<div><strong>Tú:</strong> ${msg}</div>`;
            input.value = '';

            const res = await fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: msg })
            });

            const data = await res.json();
            box.innerHTML += `<div><strong>Bot:</strong> ${data.reply}</div>`;
            box.scrollTop = box.scrollHeight;
        });
    </script>
@endsection
