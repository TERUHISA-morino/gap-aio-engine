# GAP-AIO Engine

GAP理論専用 AI集客サイト構築サービスのコアエンジン。
複数のクライアント院を **1リポジトリで一括運用** するマルチテナント構造。

公式デモ: https://gap-aio-demo.vercel.app
LP: https://www.japan-gap-association.jp/lp/aio-lp/

## 配信モード

各クライアントは `target` で配信先を選べる:

| target | 動作 | 用途 |
|---|---|---|
| `astro` | 独立Astroサイトとして Vercel に deploy | 集客特化メディアサイトを新規構築する受講生 |
| `wordpress` | 既存WPに記事だけ毎日POST | 既存WordPressサイトを持っている受講生 |
| `both` | 両方更新 | サブドメインAstroサイト + 既存WPの両運用 |

## ディレクトリ構成

```
gap_aio_engine/
├── clients/                  各院の設定・記事保存先
│   ├── gap-medical-seitai/   公式デモ院 (medical-trust テーマ)
│   ├── clean-modern-demo/    青ゴシック (clean-modern テーマ)
│   ├── warm-care-demo/       オレンジ女性向け (warm-care テーマ)
│   ├── premium-dark-demo/    黒高級 (premium-dark テーマ)
│   └── wp-example-clinic/    WordPress 配信のサンプル設定
├── packages/
│   ├── generator/            Gemini で1記事生成 + WP REST API投稿
│   ├── astro-template/       全院共通の超軽量ブログ
│   └── orchestrator/         全院ループ実行
├── shared/
│   └── gap-knowledge/        GAP理論ベースプロンプト（用語監修済）
├── scripts/
│   └── onboard.py            新規院オンボーディングCLI
└── .github/workflows/
    └── daily-generate.yml    毎朝5時 cron → 全院記事生成
```

## 4つのテーマプリセット

各院ごとに `config.json` の `theme.preset` で切替:

- **medical-trust**: 緑+明朝・伝統的な医療系（一般患者向け）
- **clean-modern**: 青+ゴシック・モダン都市型（ビジネスパーソン向け）
- **warm-care**: オレンジ+暖色・親しみ系（女性・産後特化）
- **premium-dark**: 黒+金・高級感（鍼灸自費治療・難治性症状向け）

→ 50院運用時、4テーマ × 配色微調整で「他院と被らない」サイトが作れます。

## 受講生オンボード（1コマンド）

### Astroのみ（新規構築）
```bash
python scripts/onboard.py \
    --slug clinic-001 \
    --name "○○整骨院" \
    --domain "https://blog.example.jp" \
    --specialties "腰痛,肩こり,膝痛" \
    --theme medical-trust \
    --target astro
```

### 既存WordPressに記事だけ投稿
```bash
python scripts/onboard.py \
    --slug clinic-002 \
    --name "△△整体院" \
    --domain "https://existing-clinic.jp" \
    --specialties "腰痛,自律神経" \
    --target wordpress \
    --wp-url "https://existing-clinic.jp" \
    --wp-user "admin_user" \
    --wp-pass "xxxx xxxx xxxx xxxx xxxx xxxx"
```

### 両方
```bash
python scripts/onboard.py \
    --slug clinic-003 \
    --name "□□整骨院" \
    --domain "https://blog.□□clinic.jp" \
    --specialties "腰痛,膝痛" \
    --target both \
    --theme clean-modern \
    --wp-url "https://□□clinic.jp" \
    --wp-user "..." \
    --wp-pass "..."
```

## 自動運用フロー

```
GitHub Actions cron (毎朝 UTC 20:00 = JST 05:00)
  ↓
Python orchestrator が全クライアントをループ
  ↓
各クライアント:
  1. Gemini で1記事生成
  2. clients/{slug}/articles/*.md として commit
  3. target=wordpress ならWP REST API でPOSTも実施
  ↓
git push origin master
  ↓
Vercel が target=astro/both のクライアントを自動 deploy
```

## 環境変数

```
GEMINI_API_KEY=...      # Gemini API
VERCEL_TOKEN=...        # GH Actions から Vercel deploy 用
# WP連携クライアントごとに（オプション、config.json に書いてもOK）
WP_URL_CLINIC_001=...
WP_USER_CLINIC_001=...
WP_PASS_CLINIC_001=...
```

## 月コスト目安（50院運用時）

- Gemini API: ¥3,000〜7,000
- Vercel: ¥0（無料枠内）
- GitHub Actions: ¥0〜4,000
- 合計: **¥3,000〜11,000/月**
- 売上想定: 50院 × ¥10,000 = ¥500,000/月（粗利 95%超）

## 技術スタック

- **Astro 4.16** + Tailwind CSS（静的ビルド、Lighthouse 100点設計）
- **Python 3.12** + Gemini 2.5 Flash（記事生成、REST API直叩き）
- **GitHub Actions**（毎朝5:00 JST cron + 手動実行）
- **Vercel CLI**（GH Actions から直接 deploy + alias 自動切替）
- **WordPress REST API**（Application Password 認証）
