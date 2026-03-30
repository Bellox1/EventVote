<div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
    <div>
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; border-bottom: 1px solid var(--border); padding-bottom: 15px;">
            <div>
                <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.2em;">RÉF: #{{ $camp->code }}</div>
                <h3 style="font-size: 1.8rem; color: var(--primary); font-family: 'Cormorant Garamond', serif; margin-top: 5px; line-height: 1.2;">{{ $camp->name }}</h3>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
            <div style="width: 45px; height: 45px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 1.1rem;">
                {{ substr($camp->creator->name ?? '?', 0, 1) }}
            </div>
            <div>
                <div style="font-weight: 700; color: var(--primary); font-size: 0.95rem;">{{ $camp->creator->name ?? 'Utilisateur' }}</div>
                <div style="font-size: 0.8rem; color: var(--accent); font-weight: 600;">{{ $camp->creator->phone ?? 'Pas de numéro' }}</div>
            </div>
        </div>

        <div style="margin-bottom: 20px; display: flex; gap: 20px; border-top: 1px dashed var(--border); padding-top: 20px;">
            <div style="flex: 1;">
                 <div style="font-size: 0.6rem; text-transform: uppercase; color: var(--accent); font-weight: 700; letter-spacing: 0.1em; margin-bottom: 5px;">Prix du vote</div>
                 <div style="font-weight: 700; color: var(--primary); font-size: 1.2rem; font-family: 'Cormorant Garamond', serif;">{{ $camp->vote_price == 0 ? 'Gratuit' : number_format($camp->vote_price, 0, ',', ' ') . ' FCFA' }}</div>
            </div>
            <div style="flex: 1; border-left: 1px dashed var(--border); padding-left: 20px;">
                 <div style="font-size: 0.6rem; text-transform: uppercase; color: var(--accent); font-weight: 700; letter-spacing: 0.1em; margin-bottom: 5px;">Compte de Reversement</div>
                 <div style="font-weight: 600; color: var(--primary); font-size: 0.9rem;">{{ $camp->bank_account ? $camp->bank_account : 'Non renseigné' }}</div>
            </div>
        </div>

        <div style="margin-bottom: 40px; border-top: 1px dashed var(--border); padding-top: 20px;">
            <div style="font-size: 0.65rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px;">Événement</div>
            <p style="color: var(--text-dim); font-size: 0.9rem; line-height: 1.7; font-family: 'Jost', sans-serif;">{{ $camp->description }}</p>
        </div>
    </div>

    @if($camp->status === 'pending')
        <div style="display: flex; gap: 15px; margin-top: auto;">
            <form id="ref-{{ $camp->id }}" action="{{ route('admin.campaigns.manage', $camp->id) }}" method="POST" style="flex: 1;">
                @csrf 
                <input type="hidden" name="status" value="rejected">
                <input type="hidden" name="rejection_reason" id="reason-{{ $camp->id }}">
                <button type="button" @click="Swal.fire({ title: 'Raison du refus', text: 'Optionnel. Elle sera envoyée au créateur.', input: 'textarea', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ff4444' }).then((r) => { if (r.isConfirmed) { document.getElementById('reason-{{ $camp->id }}').value = r.value; document.getElementById('ref-{{ $camp->id }}').submit(); } })" class="btn-admin-outline" style="width: 100%; padding: 12px; cursor: pointer; font-size: 0.75rem;">REFUSER</button>
            </form>
            <form id="app-{{ $camp->id }}" action="{{ route('admin.campaigns.manage', $camp->id) }}" method="POST" style="flex: 1;">
                @csrf <input type="hidden" name="status" value="active">
                <button type="button" @click="Swal.fire({ title: 'Approuver ?', text: 'La session sera publiée immédiatement.', icon: 'question', showCancelButton: true, confirmButtonColor: '#003229' }).then((r) => { if (r.isConfirmed) document.getElementById('app-{{ $camp->id }}').submit(); })" class="btn-admin-primary" style="width: 100%; padding: 12px; cursor: pointer; font-size: 0.75rem;">APPROUVER</button>
            </form>
        </div>
    @elseif($camp->status === 'rejected')
        <div style="display: flex; gap: 15px; margin-top: auto;">
            <form action="{{ route('admin.campaigns.manage', $camp->id) }}" method="POST" style="flex: 1;">
                @csrf <input type="hidden" name="status" value="active">
                <button type="submit" class="btn-admin-primary" style="width: 100%; padding: 12px; cursor: pointer; font-size: 0.75rem; letter-spacing: 0.1em; border: none;">REVALIDER & ACTIVER</button>
            </form>
        </div>
    @else
        <div style="display: flex; margin-top: auto;">
            <a href="{{ route('campaigns.show', $camp->slug) }}" target="_blank" class="btn-admin-outline" style="display: block; text-align: center; padding: 12px; width: 100%; text-decoration: none;">VOIR LE SCRUTIN</a>
        </div>
    @endif
</div>
