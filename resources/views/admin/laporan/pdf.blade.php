<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 20px; }
    h1 { font-size: 18px; color: #1f2937; margin-bottom: 4px; }
    h2 { font-size: 13px; color: #374151; margin: 14px 0 6px; }
    .subtitle { color: #6b7280; font-size: 10px; margin-bottom: 16px; }
    .header { border-bottom: 2px solid #f59e0b; padding-bottom: 10px; margin-bottom: 16px; }
    .stats { display: flex; gap: 12px; margin-bottom: 14px; }
    .stat-box { background: #f9fafb; border: 1px solid #e5e7eb; padding: 8px 14px; border-radius: 8px; text-align: center; flex: 1; }
    .stat-val { font-size: 20px; font-weight: bold; color: #92400e; }
    .stat-lbl { font-size: 9px; color: #6b7280; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f59e0b; color: #1f2937; padding: 6px 10px; text-align: left; font-size: 10px; font-weight: bold; }
    td { padding: 5px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
    tr:nth-child(even) td { background: #f9fafb; }
    .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb; font-size: 9px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h1>📊 Laporan Bulanan BondoWisata</h1>
    <p class="subtitle">Periode: {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }} | Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="stats">
    <div class="stat-box"><div class="stat-val">{{ $totalRestoran }}</div><div class="stat-lbl">Total Restoran</div></div>
    <div class="stat-box"><div class="stat-val">{{ $totalUlasan }}</div><div class="stat-lbl">Ulasan Bulan Ini</div></div>
    <div class="stat-box"><div class="stat-val">{{ number_format($avgRating, 1) }}</div><div class="stat-lbl">Rata-rata Rating</div></div>
</div>

<h2>Top 10 Rekomendasi SAW</h2>
<table>
    <thead>
        <tr>
            <th>Peringkat</th>
            <th>Nama Restoran</th>
            <th>Kecamatan</th>
            <th>Skor SAW</th>
            <th>Rating</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topRestoran as $h)
        <tr>
            <td style="text-align:center;font-weight:bold;">{{ $h->peringkat }}</td>
            <td>{{ $h->restoran?->nama_usaha }}</td>
            <td>{{ $h->restoran?->kecamatan?->nama }}</td>
            <td style="text-align:center;font-family:monospace;font-weight:bold;">{{ number_format($h->skor_saw_final, 4) }}</td>
            <td style="text-align:center;">{{ number_format($h->restoran?->avg_rating ?? 0, 1) }}★</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Dibuat oleh sistem BondoWisata | Dinas Pariwisata Kabupaten Bondowoso
</div>
</body>
</html>
