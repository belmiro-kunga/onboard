<div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-slate-700/50 overflow-hidden">
    <div class="bg-gradient-to-r from-purple-500/10 to-blue-500/10 border-b border-slate-700/50 p-6">
        <h3 class="text-xl font-bold text-white flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Confirmação Final
        </h3>
        <p class="text-slate-400 mt-1">Revise os dados antes de criar o curso</p>
    </div>
    <div class="p-6 space-y-6">
        <div class="text-white text-lg font-semibold mb-4">Resumo dos dados preenchidos:</div>
        <ul class="text-slate-200 space-y-2">
            <li><span class="font-bold">Título:</span> <span id="review-title"></span></li>
            <li><span class="font-bold">Descrição Curta:</span> <span id="review-short-description"></span></li>
            <li><span class="font-bold">Tipo:</span> <span id="review-type"></span></li>
            <li><span class="font-bold">Dificuldade:</span> <span id="review-difficulty"></span></li>
            <li><span class="font-bold">Duração:</span> <span id="review-duration"></span></li>
            <li><span class="font-bold">Ordem:</span> <span id="review-order"></span></li>
            <li><span class="font-bold">Tags:</span> <span id="review-tags"></span></li>
            <li><span class="font-bold">Status:</span> <span id="review-status"></span></li>
        </ul>
        <div class="mt-6 text-slate-400 text-sm">Se necessário, volte e ajuste as informações antes de criar o curso.</div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setReviewField(id, value) {
                var el = document.getElementById(id);
                if (el) el.textContent = value || '-';
            }
            setReviewField('review-title', document.getElementById('title')?.value);
            setReviewField('review-short-description', document.getElementById('short_description')?.value);
            setReviewField('review-type', document.getElementById('type')?.selectedOptions[0]?.textContent);
            setReviewField('review-difficulty', document.getElementById('difficulty_level')?.selectedOptions[0]?.textContent);
            setReviewField('review-duration', document.getElementById('duration_hours')?.value);
            setReviewField('review-order', document.getElementById('order_index')?.value);
            setReviewField('review-tags', document.getElementById('tags')?.value);
            setReviewField('review-status', document.getElementById('is_active')?.checked ? 'Ativo' : 'Inativo');
        });
    </script>
</div> 