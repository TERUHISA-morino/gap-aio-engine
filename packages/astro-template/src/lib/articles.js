// クライアント記事ディレクトリから MD ファイルを読み込んで返す
import fs from 'node:fs';
import path from 'node:path';

const slug = process.env.CLIENT_SLUG || 'gap-medical-seitai';
const articlesDir = path.resolve(`../../clients/${slug}/articles`);

function parseFrontmatter(text) {
  const match = text.match(/^---\n([\s\S]*?)\n---\n([\s\S]*)$/);
  if (!match) return { data: {}, content: text };
  const fm = match[1];
  const body = match[2];
  const data = {};
  for (const line of fm.split('\n')) {
    const m = line.match(/^([a-zA-Z0-9_]+):\s*(.*)$/);
    if (!m) continue;
    let val = m[2].trim();
    if (val.startsWith('"') && val.endsWith('"')) val = val.slice(1, -1);
    if (val.startsWith('[') && val.endsWith(']')) {
      val = val.slice(1, -1).split(',').map(s => s.trim().replace(/^"|"$/g, '')).filter(Boolean);
    }
    data[m[1]] = val;
  }
  return { data, content: body };
}

export function getAllArticles() {
  if (!fs.existsSync(articlesDir)) return [];
  const files = fs.readdirSync(articlesDir).filter(f => f.endsWith('.md'));
  const articles = files.map((file) => {
    const raw = fs.readFileSync(path.join(articlesDir, file), 'utf8');
    const { data, content } = parseFrontmatter(raw);
    return {
      slug: file.replace(/\.md$/, ''),
      data,
      content,
      filename: file,
    };
  });
  // 日付降順
  return articles.sort((a, b) => {
    return (b.data.date || '').localeCompare(a.data.date || '');
  });
}

export function getArticleBySlug(slug) {
  return getAllArticles().find(a => a.slug === slug);
}

// 簡易 Markdown→HTML（プロダクションでは marked 等を使うのが望ましい）
export function mdToHtml(md) {
  let html = md;
  // 見出し
  html = html.replace(/^### (.+)$/gm, '<h3>$1</h3>');
  html = html.replace(/^## (.+)$/gm, '<h2>$1</h2>');
  // 強調
  html = html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
  // リスト
  html = html.replace(/^- (.+)$/gm, '<li>$1</li>');
  html = html.replace(/(<li>[\s\S]+?<\/li>\n?)+/g, (m) => `<ul>${m}</ul>`);
  // 段落
  html = html.split(/\n\n+/).map(p => {
    const trimmed = p.trim();
    if (!trimmed) return '';
    if (trimmed.startsWith('<h') || trimmed.startsWith('<ul') || trimmed.startsWith('<ol')) return trimmed;
    return `<p>${trimmed.replace(/\n/g, '<br>')}</p>`;
  }).join('\n');
  return html;
}
