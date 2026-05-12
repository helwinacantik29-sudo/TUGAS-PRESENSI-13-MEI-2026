<?php
$nama = "";
$status = "";
$pesan = "";
$pesan_class = "";
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars(trim($_POST["nama"]));
    $status = htmlspecialchars($_POST["status"]);
    $submitted = true;
    $waktu = date("d M Y, H:i:s");

    if ($status == "hadir") {
        $pesan = "✅ Anda hadir hari ini";
        $pesan_class = "hadir";
        $emoji = "✅";
    } elseif ($status == "izin") {
        $pesan = "📋 Anda izin";
        $pesan_class = "izin";
        $emoji = "📋";
    } elseif ($status == "sakit") {
        $pesan = "💊 Semoga cepat sembuh";
        $pesan_class = "sakit";
        $emoji = "💊";
    } else {
        $pesan = "❌ Anda tidak hadir hari ini";
        $pesan_class = "tidak-hadir";
        $emoji = "❌";
    }
}

$tanggal_hari_ini = date("l, d F Y");
$jam_sekarang = date("H:i");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;700;800&display=swap');

:root {
    --bg: #ffffff;
    --surface: #1a1a1a;
    --surface-2: #242424;
    --border: #2e2e2e;
    --text: #60a5fa;
    --text-muted: #7a7a7a;
    --accent: #92b6e8;
    --accent-2: #f5f0e8;

    --hadir-color: #60a5fa;
    --izin-color: #60a5fa;
    --sakit-color: #60a5fa;
    --tidakhadir-color: #60a5fa;

    --font-display: 'Syne', sans-serif;
    --font-mono: 'Space Mono', monospace;

    --radius: 4px;
    --radius-lg: 12px;
    --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: smooth; }

body {
    font-family: var(--font-display);
    background-color: var(--bg);
    color: var(--text);
    min-height: 100vh;
    line-height: 1.6;
    overflow-x: hidden;
    position: relative;
}

.noise {
    pointer-events: none;
    position: fixed;
    inset: 0;
    z-index: 0;
    opacity: 0.03;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-size: 200px;
}

.wrapper {
    position: relative;
    z-index: 1;
    max-width: 1100px;
    margin: 0 auto;
    padding: 24px 20px 40px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    gap: 28px;
}

.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border);
}

.header-left { display: flex; flex-direction: column; gap: 4px; }

.badge {
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 3px;
    color: var(--accent);
    text-transform: uppercase;
}

.logo {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: clamp(28px, 5vw, 46px);
    line-height: 1;
    letter-spacing: -2px;
    color: var(--text);
}

.logo .accent { color: var(--accent); }

.live-clock {
    text-align: right;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 12px 20px;
}

.clock-date {
    font-family: var(--font-mono);
    font-size: 10px;
    color: var(--text-muted);
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.clock-time {
    font-family: var(--font-mono);
    font-size: 28px;
    font-weight: 700;
    color: var(--accent);
    letter-spacing: 2px;
    line-height: 1;
}

.main-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    flex: 1;
}

@media (max-width: 720px) {
    .main-grid { grid-template-columns: 1fr; }
}

.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 32px 28px;
    position: relative;
    overflow: hidden;
    transition: border-color var(--transition);
}

.card:hover { border-color: #444; }

.card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), transparent);
}

.card-tag {
    font-family: var(--font-mono);
    font-size: 9px;
    letter-spacing: 3px;
    color: var(--text-muted);
    text-transform: uppercase;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-tag::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

.card-title {
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -0.5px;
    margin-bottom: 6px;
    color: var(--text);
}

.card-sub {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 28px;
}

.field-group { margin-bottom: 22px; }

.field-group label {
    display: block;
    font-family: var(--font-mono);
    font-size: 11px;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 10px;
}

.input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 14px;
    font-size: 16px;
    pointer-events: none;
    z-index: 1;
}

input[type="text"] {
    width: 100%;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    color: var(--text);
    font-family: var(--font-display);
    font-size: 15px;
    padding: 13px 14px 13px 44px;
    transition: border-color var(--transition), box-shadow var(--transition);
    outline: none;
}

input[type="text"]::placeholder { color: #555; }

input[type="text"]:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(232, 212, 77, 0.12);
}

.radio-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.radio-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 14px 10px;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    cursor: pointer;
    transition: all var(--transition);
    position: relative;
    user-select: none;
}

.radio-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.radio-card:hover {
    border-color: #555;
    transform: translateY(-2px);
}

.radio-icon { font-size: 22px; }

.radio-label {
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-muted);
    transition: color var(--transition);
}

.radio-card.selected.hadir {
    border-color: var(--hadir-color);
    background: rgba(74, 222, 128, 0.08);
    box-shadow: 0 0 16px rgba(74, 222, 128, 0.15);
}
.radio-card.selected.hadir .radio-label { color: var(--hadir-color); }

.radio-card.selected.izin {
    border-color: var(--izin-color);
    background: rgba(96, 165, 250, 0.08);
    box-shadow: 0 0 16px rgba(96, 165, 250, 0.15);
}
.radio-card.selected.izin .radio-label { color: var(--izin-color); }

.radio-card.selected.sakit {
    border-color: var(--sakit-color);
    background: rgba(251, 146, 60, 0.08);
    box-shadow: 0 0 16px rgba(251, 146, 60, 0.15);
}
.radio-card.selected.sakit .radio-label { color: var(--sakit-color); }

.radio-card.selected.tidak-hadir {
    border-color: var(--tidakhadir-color);
    background: rgba(248, 113, 113, 0.08);
    box-shadow: 0 0 16px rgba(248, 113, 113, 0.15);
}
.radio-card.selected.tidak-hadir .radio-label { color: var(--tidakhadir-color); }

.btn-submit {
    width: 100%;
    margin-top: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px 24px;
    background: var(--accent);
    color: #0a0a0a;
    border: none;
    border-radius: var(--radius);
    font-family: var(--font-mono);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all var(--transition);
}

.btn-submit:hover {
    background: #f0dc5d;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(232, 212, 77, 0.3);
}

.btn-submit:active { transform: translateY(0); }

.result-card {
    display: flex;
    flex-direction: column;
}

.result-card.active::before {
    background: linear-gradient(90deg, var(--hadir-color), var(--accent));
}

@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.animate-in {
    animation: fadeSlideIn 0.5s ease forwards;
}

.result-status {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 20px;
    border-radius: var(--radius);
    margin-bottom: 22px;
    border: 1px solid transparent;
}

.result-status.hadir       { background: rgba(74,222,128,0.1); border-color: rgba(74,222,128,0.3); }
.result-status.izin        { background: rgba(96,165,250,0.1); border-color: rgba(96,165,250,0.3); }
.result-status.sakit       { background: rgba(251,146,60,0.1); border-color: rgba(251,146,60,0.3); }
.result-status.tidak-hadir { background: rgba(248,113,113,0.1); border-color: rgba(248,113,113,0.3); }

.status-emoji { font-size: 28px; }

.status-text {
    font-size: 20px;
    font-weight: 800;
    letter-spacing: -0.5px;
    text-transform: capitalize;
}

.result-info {
    display: flex;
    flex-direction: column;
    gap: 0;
    margin-bottom: 20px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    gap: 12px;
}

.info-row:last-child { border-bottom: none; }

.info-label {
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-muted);
    white-space: nowrap;
}

.info-value {
    font-size: 14px;
    font-weight: 600;
    text-align: right;
    word-break: break-word;
}

.info-value.highlight.hadir        { color: var(--hadir-color); }
.info-value.highlight.izin         { color: var(--izin-color); }
.info-value.highlight.sakit        { color: var(--sakit-color); }
.info-value.highlight.tidak-hadir  { color: var(--tidakhadir-color); }

.pesan-box {
    padding: 14px 18px;
    border-radius: var(--radius);
    font-family: var(--font-mono);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 22px;
    text-align: center;
    border-left: 3px solid;
}

.pesan-box.hadir        { background: rgba(74,222,128,0.08); border-color: var(--hadir-color); color: var(--hadir-color); }
.pesan-box.izin         { background: rgba(96,165,250,0.08); border-color: var(--izin-color); color: var(--izin-color); }
.pesan-box.sakit        { background: rgba(251,146,60,0.08); border-color: var(--sakit-color); color: var(--sakit-color); }
.pesan-box.tidak-hadir  { background: rgba(248,113,113,0.08); border-color: var(--tidakhadir-color); color: var(--tidakhadir-color); }

.qr-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 20px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 20px;
}

.qr-label {
    font-family: var(--font-mono);
    font-size: 9px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--text-muted);
}

.qr-box {
    padding: 12px;
    background: #f5f0e8;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.4);
}

.qr-box img { display: block; border-radius: 2px; }

.qr-caption {
    font-family: var(--font-mono);
    font-size: 10px;
    color: var(--text-muted);
    letter-spacing: 0.5px;
    text-align: center;
}

.btn-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 20px;
    background: transparent;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    color: var(--text-muted);
    font-family: var(--font-mono);
    font-size: 11px;
    letter-spacing: 1px;
    text-decoration: none;
    text-transform: uppercase;
    cursor: pointer;
    transition: all var(--transition);
}

.btn-reset:hover {
    border-color: var(--accent);
    color: var(--accent);
    transform: translateY(-1px);
}

.empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    text-align: center;
    padding: 40px 20px;
}

.empty-icon {
    font-size: 48px;
    opacity: 0.4;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-8px); }
}

.empty-state p {
    color: var(--text-muted);
    font-size: 14px;
    line-height: 1.8;
}

.footer {
    text-align: center;
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 1px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.dot { color: var(--accent); }

::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: #444; }

@media (max-width: 480px) {
    .wrapper { padding: 16px 14px 30px; }
    .header { flex-direction: column; align-items: flex-start; gap: 14px; }
    .live-clock { text-align: left; width: 100%; }
    .clock-time { font-size: 22px; }
    .card { padding: 22px 18px; }
    .radio-grid { grid-template-columns: 1fr 1fr; }
}
    </style>
</head>
<body>

<div class="noise"></div>

<div class="wrapper">

    <header class="header">
        <div class="header-left">
            <span class="badge">SISTEM ABSENSI</span>
            <h1 class="logo">ABSENSI<span class="accent"></span></h1>
        </div>
        <div class="header-right">
            <div class="live-clock">
                <div class="clock-date"><?php echo $tanggal_hari_ini; ?></div>
                <div class="clock-time" id="live-time"><?php echo $jam_sekarang; ?></div>
            </div>
        </div>
    </header>

    <main class="main-grid">

        <div class="card form-card">
            <div class="card-tag">INPUT DATA</div>
            <h2 class="card-title">Form Absensi</h2>
            <p class="card-sub">Isi data kehadiran Anda hari ini</p>

            <form method="POST" action="index.php" id="absenForm">

                <div class="field-group">
                    <label for="nama">Nama Mahasiswa</label>
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            placeholder="Masukkan nama lengkap"
                            value="<?php echo $submitted ? htmlspecialchars($nama) : ''; ?>"
                            required
                            autocomplete="off"
                        >
                    </div>
                </div>

                <div class="field-group">
                    <label>Status Kehadiran</label>
                    <div class="radio-grid">
                        <label class="radio-card <?php echo ($status == 'hadir') ? 'selected hadir' : ''; ?>">
                            <input type="radio" name="status" value="hadir" <?php echo ($status == 'hadir') ? 'checked' : ''; ?>>
                            <span class="radio-icon">✅</span>
                            <span class="radio-label">Hadir</span>
                        </label>
                        <label class="radio-card <?php echo ($status == 'izin') ? 'selected izin' : ''; ?>">
                            <input type="radio" name="status" value="izin" <?php echo ($status == 'izin') ? 'checked' : ''; ?>>
                            <span class="radio-icon">📋</span>
                            <span class="radio-label">Izin</span>
                        </label>
                        <label class="radio-card <?php echo ($status == 'sakit') ? 'selected sakit' : ''; ?>">
                            <input type="radio" name="status" value="sakit" <?php echo ($status == 'sakit') ? 'checked' : ''; ?>>
                            <span class="radio-icon">💊</span>
                            <span class="radio-label">Sakit</span>
                        </label>
                        <label class="radio-card <?php echo ($status == 'tidak hadir') ? 'selected tidak-hadir' : ''; ?>">
                            <input type="radio" name="status" value="tidak hadir" <?php echo ($status == 'tidak hadir') ? 'checked' : ''; ?>>
                            <span class="radio-icon">❌</span>
                            <span class="radio-label">Tidak Hadir</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span>KIRIM ABSENSI</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>

            </form>
        </div>

        <div class="card result-card <?php echo $submitted ? 'active' : ''; ?>">
            <div class="card-tag">HASIL ABSENSI</div>

            <?php if ($submitted): ?>
            <div class="result-content animate-in">
                <div class="result-status <?php echo $pesan_class; ?>">
                    <span class="status-emoji"><?php echo $emoji; ?></span>
                    <span class="status-text"><?php echo ucfirst($status); ?></span>
                </div>

                <div class="result-info">
                    <div class="info-row">
                        <span class="info-label">Nama Mahasiswa</span>
                        <span class="info-value"><?php echo $nama; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value highlight <?php echo $pesan_class; ?>"><?php echo ucfirst($status); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Waktu</span>
                        <span class="info-value"><?php echo $waktu; ?></span>
                    </div>
                </div>

                <div class="pesan-box <?php echo $pesan_class; ?>">
                    <?php echo $pesan; ?>
                </div>

                <a href="index.php" class="btn-reset">← Absen Lagi</a>
            </div>

            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <p>Isi form di sebelah kiri<br>untuk melihat hasil absensi</p>
            </div>
            <?php endif; ?>
        </div>

    </main>

                
</div>

<script>
function updateClock() {
    const now = new Date();
    const jam = now.getHours().toString().padStart(2, '0');
    const menit = now.getMinutes().toString().padStart(2, '0');
    const detik = now.getSeconds().toString().padStart(2, '0');
    const el = document.getElementById('live-time');
    if (el) el.textContent = `${jam}:${menit}:${detik}`;
}
setInterval(updateClock, 1000);
updateClock();

document.querySelectorAll('.radio-card input').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.radio-card').forEach(c => {
            c.classList.remove('selected', 'hadir', 'izin', 'sakit', 'tidak-hadir');
        });
        const parent = this.closest('.radio-card');
        parent.classList.add('selected', this.value.replace(' ', '-'));
    });
});
</script>

</body>
</html>