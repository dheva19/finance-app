
<div id="note-modal" class="hidden">
    <div id="note-modal-overlay" class="fixed top-0 left-0 w-screen h-screen bg-black opacity-50 z-98" onclick="closeNoteModal()">
    </div>
    <div class="fixed top-1/5 left-1/3 flex flex-col z-99">
        <div class="bg-blue-500 text-white p-5 w-125 rounded-t-lg flex justify-between items-center">
            <h3 class="text-xl font-semibold">Catatan</h3>
            <button onclick="closeNoteModal()" class="text-xl cursor-pointer hover:opacity-70"><x-icon.close/></button>
        </div>
        <form id="note-update-form" method="POST" class="bg-white w-125 p-5 min-h-56 rounded-b-lg">
            @method("PATCH")
            @csrf
            <div class="mb-3">
                <x-ui.textarea id="note" name="note" class="w-full" rows="7">
                </x-ui.textarea>
            </div>

            <div class="flex items-center justify-end gap-2">
                <x-ui.button type="button" variant="outline" onclick="closeNoteModal()">Tutup</x-ui.button>
                <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeNoteModal(){
        document.querySelector("#note-modal").classList.add('hidden');
    }

    function openNoteModal(note, noteId){
        document.querySelector("#note-modal").classList.remove('hidden');
        document.querySelector("#note-update-form").setAttribute('action', `/transactions/${noteId}`);
        document.querySelector("#note").value = note;
    }
</script>
