<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 20px; }
    h1 { font-size: 18px; color: #1f2937; margin-bottom: 4px; }
    .subtitle { color: #6b7280; font-size: 10px; margin-bottom: 16px; }
    .header { border-bottom: 2px solid #f59e0b; padding-bottom: 10px; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th { background: #f59e0b; color: #1f2937; padding: 7px 10px; text-align: left; font-size: 10px; font-weight: bold; }
    td { padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
    tr:nth-child(even) td { background: #f9fafb; }
    .rank-1 { background: #fef3c7; font-weight: bold; }
    .rank-2 { background: #f3f4f6; }
    .rank-3 { background: #fff7ed; }
    .score { font-family: monospace; font-weight: bold; color: #92400e; }
    .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb; font-size: 9px; color: #9ca3af; text-align: center; }
    .weights { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
    .weight-badge { background: #fef3c7; border: 1px solid #fcd34d; padding: 3px 8px; border-radius: 12px; font-size: 9px; font-weight: bold; color: #92400e; }
</style>
</head>
<body>
<div class="header">
    <h1>🏆 Laporan Rekomendasi SAW</h1>
    <p class="subtitle">BondoWisata — Dinas Pariwisata Kabupaten Bondowoso | Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    <div class="weights">
        <span class="weight-badge">Rating: {{ number_format($config->bobot_rating, 0) }}%</span>
        <span class="weight-badge">Sentimen: {{ number_format($config->bobot_sentimen, 0) }}%</span>
        <span class="weight-badge">Harga: {{ number_format($config->bobot_harga, 0) }}%</span>
        <span class="weight-badge">Popularitas: {{ number_format($config->bobot_popularitas, 0) }}%</span>
        <span class="weight-badge">Kebaruan: {{ number_format($config->bobot_kebaruan, 0) }}%</span>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Restoran</th>
            <th>Kecamatan</th>
            <th>Rating</th>
            <th>Sentimen</th>
            <th>Harga</th>
            <th>Popularitas</th>
            <th>Kebaruan</th>
            <th>SAW Final</th>
        </tr>
    </thead>
    <tbody>
        @foreach($hasil as $h)
        <tr class="{{ $h->peringkat <= 3 ? 'rank-'.$h->peringkat : '' }}">
            <td style="text-align:center;font-weight:bold;">{{ $h->peringkat }}</td>
            <td>{{ $h->restoran?->nama_usaha }}</td>
            <td>{{ $h->restoran?->kecamatan?->nama }}</td>
            <td style="text-align:center;">{{ number_format($h->skor_rating, 4) }}</td>
            <td style="text-align:center;">{{ number_format($h->skor_sentimen, 4) }}</td>
            <td style="text-align:center;">{{ number_format($h->skor_harga, 4) }}</td>
            <td style="text-align:center;">{{ number_format($h->skor_popularitas, 4) }}</td>
            <td style="text-align:center;">{{ number_format($h->skor_kebaruan, 4) }}</td>
            <td class="score" style="text-align:center;">{{ number_format($h->skor_saw_final, 4) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Total restoran: {{ $hasil->count() }} | Algoritma: Simple Additive Weighting (SAW) |
    Dihitung: {{ $hasil->first()?->dihitung_at?->format('d/m/Y H:i') ?? '-' }}
</div>
</body>
</html>
