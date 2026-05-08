// build時に CLIENT_SLUG 環境変数で上書きされる
import fs from 'node:fs';
import path from 'node:path';

const slug = process.env.CLIENT_SLUG || 'gap-medical-seitai';
const configPath = path.resolve(`../../clients/${slug}/config.json`);

let config;
if (fs.existsSync(configPath)) {
  config = JSON.parse(fs.readFileSync(configPath, 'utf8'));
} else {
  config = {
    slug,
    name: 'GAPメディカル整体院',
    tagline: 'GAP理論に基づく根本治療を提供する整体院',
    author: 'GAPメディカル整体院',
    domain: 'http://localhost:4321',
    address: '',
    contactUrl: '#',
    specialties: ['腰痛', '肩こり'],
  };
}

export default config;
