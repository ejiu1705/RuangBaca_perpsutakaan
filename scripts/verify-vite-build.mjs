import fs from 'node:fs';
import path from 'node:path';

const projectRoot = process.cwd();
const manifestPath = path.join(projectRoot, 'public', 'build', 'manifest.json');

function fail(message) {
    console.error(`[RuangBaca] Pemeriksaan aset gagal: ${message}`);
    process.exit(1);
}

if (!fs.existsSync(manifestPath)) {
    fail('public/build/manifest.json tidak ditemukan. Jalankan npm run build.');
}

let manifest;
try {
    manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
} catch {
    fail('manifest Vite bukan JSON yang valid.');
}

const cssEntry = manifest['resources/css/app.css'];
const jsEntry = manifest['resources/js/app.js'];
if (!cssEntry?.file || !jsEntry?.file) {
    fail('Entry CSS atau JavaScript RuangBaca tidak tercantum di manifest Vite.');
}

const cssFiles = [...new Set([cssEntry.file, ...(cssEntry.css ?? [])])];
const cssContent = cssFiles.map((file) => {
    const absolute = path.resolve(projectRoot, 'public', 'build', file);
    if (!absolute.startsWith(path.resolve(projectRoot, 'public', 'build') + path.sep) || !fs.existsSync(absolute)) {
        fail(`Berkas CSS hasil build tidak ditemukan: ${file}`);
    }
    return fs.readFileSync(absolute, 'utf8');
}).join('\n');

for (const selector of ['.auth-shell', '.auth-card', '.app-shell', '.app-sidebar', '.rb-card']) {
    if (!cssContent.includes(selector)) {
        fail(`Desain inti tidak ditemukan pada bundle CSS: ${selector}`);
    }
}

const jsPath = path.resolve(projectRoot, 'public', 'build', jsEntry.file);
if (!jsPath.startsWith(path.resolve(projectRoot, 'public', 'build') + path.sep) || !fs.existsSync(jsPath)) {
    fail(`Berkas JavaScript hasil build tidak ditemukan: ${jsEntry.file}`);
}

console.log('[RuangBaca] Aset Vite lengkap: CSS login, dashboard, layout, dan JavaScript tersedia.');
