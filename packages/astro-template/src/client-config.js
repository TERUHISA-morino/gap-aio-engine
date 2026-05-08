// build時に CLIENT_SLUG 環境変数で対象クライアント切替
import fs from 'node:fs';
import path from 'node:path';

const slug = process.env.CLIENT_SLUG || 'gap-medical-seitai';
const configPath = path.resolve(`../../clients/${slug}/config.json`);

let config;
if (fs.existsSync(configPath)) {
  config = JSON.parse(fs.readFileSync(configPath, 'utf8'));
} else {
  config = { slug, name: 'デモ院', tagline: '', domain: 'http://localhost:4322' };
}

// テーマプリセット
const presets = {
  'medical-trust': {
    primary: '#047857',
    primaryDark: '#065f46',
    accent: '#fbbf24',
    bgGrad: 'from-emerald-50 via-white to-amber-50',
    fontTitle: 'serif',
    heroStyle: 'split',
  },
  'clean-modern': {
    primary: '#0284c7',
    primaryDark: '#075985',
    accent: '#f97316',
    bgGrad: 'from-sky-50 via-white to-rose-50',
    fontTitle: 'sans',
    heroStyle: 'centered',
  },
  'warm-care': {
    primary: '#c2410c',
    primaryDark: '#9a3412',
    accent: '#facc15',
    bgGrad: 'from-orange-50 via-white to-yellow-50',
    fontTitle: 'serif',
    heroStyle: 'image-bg',
  },
  'premium-dark': {
    primary: '#1e293b',
    primaryDark: '#0f172a',
    accent: '#fde68a',
    bgGrad: 'from-slate-900 via-slate-800 to-slate-900',
    fontTitle: 'serif',
    heroStyle: 'dark-overlay',
  },
};

const presetName = config?.theme?.preset || 'medical-trust';
const preset = presets[presetName] || presets['medical-trust'];

// configのthemeで個別上書き可
config.theme = {
  ...preset,
  ...(config.theme || {}),
  presetName,
};

export default config;
