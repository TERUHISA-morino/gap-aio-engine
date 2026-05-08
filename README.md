# GAP-AIO Engine

GAP理論専用 AI集客サイト構築サービスのコアエンジン。
複数のクライアント院（モニター5名→本募集10〜50名）を **1リポジトリで一括運用**するマルチテナント構造。

## 構成

```
gap_aio_engine/
├── clients/                  各院の設定・記事保存先
│   └── gap-medical-seitai/   公式デモ院（架空）
│       ├── config.json       院情報・ドメイン・専門分野
│       ├── topics.json       記事トピックキュー
│       └── articles/         生成記事（毎日1本commit）
├── packages/
│   ├── generator/            Gemini で1記事生成
│   ├── astro-template/       全院共通の超軽量ブログ
│   └── orchestrator/         全院ループ実行
├── shared/
│   ├── gap-knowledge/        GAP理論ベースプロンプト
│   └── schemas/              schema.org JSON-LD AIO最適化
├── scripts/
│   └── onboard.py            新規院オンボーディングCLI
└── .github/workflows/
    └── daily-generate.yml    毎朝5時 cron → 全院記事生成
```

## 開発・運用フロー

### 新規院オンボード
```bash
python scripts/onboard.py \
    --slug clinic-001 \
    --name "○○整骨院" \
    --domain "blog.example.jp" \
    --specialties "腰痛,肩こり,膝痛"
```

### 記事生成（手動）
```bash
python packages/generator/generate.py --client gap-medical-seitai
```

### 全院記事生成（毎日自動）
GitHub Actions の cron が `.github/workflows/daily-generate.yml` を毎朝5時に起動。

## 環境変数

`.env`:
```
GEMINI_API_KEY=your_key
VERCEL_TOKEN=your_token
GITHUB_TOKEN=auto
```

## 技術スタック

- **Astro 4.x** + Tailwind CSS（静的書き出し、Lighthouse 100点設計）
- **Python 3.12** + Gemini 2.5 Pro（記事生成）
- **GitHub Actions**（毎日 cron）
- **Vercel**（CDN edge配信、無料枠で50院運用可）
