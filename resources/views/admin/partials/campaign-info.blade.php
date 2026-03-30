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

        <div style="margin-bottom: 40px;">
            <div style="font-size: 0.65rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px;">Événement</div>
            <p style="color: var(--text-dim); font-size: 0.9rem; line-height: 1.7; font-family: 'Jost', sans-serif;">{{ $camp->description }}</p>
        </div>
    </div>

    <div style="display: flex; gap: 15px; margin-top: auto;">
        <form id="ref-{{ $camp->id }}" action="{{ route('admin.campaigns.manage', $camp->id) }}" method="POST" style="flex: 1;">
            @csrf <input type="hidden" name="status" value="rejected">
            <button type="button" @click="Swal.fire({ title: 'Refuser ?', text: 'Cette action informera le créateur.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ff4444' }).then((r) => { if (r.isConfirmed) document.getElementById('ref-{{ $camp->id }}').submit(); })" class="btn-admin-outline" style="width: 100%; padding: 12px; cursor: pointer; font-size: 0.75rem;">REFUSER</button>
        </form>
        <form id="app-{{ $camp->id }}" action="{{ route('admin.campaigns.manage', $camp->id) }}" method="POST" style="flex: 1;">
            @csrf <input type="hidden" name="status" value="active">
            <button type="button" @click="Swal.fire({ title: 'Approuver ?', text: 'La session sera publiée immédiatement.', icon: 'question', showCancelButton: true, confirmButtonColor: '#003229' }).then((r) => { if (r.isConfirmed) document.getElementById('app-{{ $camp->id }}').submit(); })" class="btn-admin-primary" style="width: 100%; padding: 12px; cursor: pointer; font-size: 0.75rem;">APPROUVER</button>
        </form>
    </div>
</div>
