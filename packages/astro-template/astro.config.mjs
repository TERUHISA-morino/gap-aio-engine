import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind';
import fs from 'node:fs';
import path from 'node:path';

// CLIENT_SLUG 環境変数で対象クライアントを切替
// 例: CLIENT_SLUG=gap-medical-seitai npm run build
const CLIENT_SLUG = process.env.CLIENT_SLUG || 'gap-medical-seitai';
const clientConfigPath = path.resolve(`../../clients/${CLIENT_SLUG}/config.json`);
let clientConfig = { domain: 'http://localhost:4321' };
if (fs.existsSync(clientConfigPath)) {
  clientConfig = JSON.parse(fs.readFileSync(clientConfigPath, 'utf8'));
}

export default defineConfig({
  site: clientConfig.domain || 'https://example.com',
  integrations: [
    tailwind(),
  ],
  build: {
    inlineStylesheets: 'auto',
  },
  server: { host: '127.0.0.1', port: 4322 },
});
