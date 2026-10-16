<div class="panel overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    @if ($showCafe)<th class="d-none d-md-table-cell">Cafe</th>@endif
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th class="d-none d-sm-table-cell">Tanggal</th>
                    @if (Auth::user()->isAdmin())<th></th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr>
                        <td class="small fw-semibold text-nowrap">{{ $review->user->name ?? 'Pengguna' }}</td>
                        @if ($showCafe)<td class="small d-none d-md-table-cell">{{ $review->cafe->nama ?? '-' }}</td>@endif
                        <td class="text-nowrap small">@include('partials.stars', ['value' => $review->rating])</td>
                        <td class="small" style="min-width: 200px">{{ $review->komentar }}</td>
                        <td class="small text-muted text-nowrap d-none d-sm-table-cell">{{ $review->created_at->translatedFormat('d M Y') }}</td>
                        @if (Auth::user()->isAdmin())
                            <td class="text-end">
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" data-confirm="Hapus review ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-state">Belum ada review.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $reviews->links() }}</div>
