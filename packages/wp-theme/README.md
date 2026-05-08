# GAP-AIO Clinic - WordPressテーマ

GAP理論実践院専用 WordPress テーマ。
4スタイル（medical-trust / clean-modern / warm-care / premium-dark）を Customizer から切り替え可能。

## 受講生向けインストール手順

### 1. ZIP化
```bash
cd C:\Users\tmori\gap_aio_engine\packages
# wp-theme フォルダごと ZIP に圧縮
# 出力例: gap-aio-clinic.zip
```

または PowerShell:
```powershell
Compress-Archive -Path .\wp-theme\* -DestinationPath .\gap-aio-clinic.zip -Force
```

### 2. WordPress 管理画面でアップロード
1. WP管理画面 → **外観 → テーマ → 新規追加 → テーマのアップロード**
2. `gap-aio-clinic.zip` を選択 → **今すぐインストール**
3. **有効化**

### 3. Customizer で院情報を設定
1. **外観 → カスタマイズ → GAP-AIO 院設定** を開く
2. 各セクションに沿って入力:
   - ① テーマスタイル ← 4種類から選択
   - ② 院の基本情報 ← 院名・電話・LINE・住所など
   - ③ 専門分野 ← カンマ区切り（例: 腰痛,肩こり,頭痛）
   - ④〜⑨ 各セクション（特徴・施術方針・症状・料金・スタッフ・声）

### 4. 固定ページ「フロントページ」を設定
1. WP管理画面 → **設定 → 表示設定**
2. 「ホームページの表示」を **固定ページ** に変更
3. 任意の固定ページを「ホームページ」に指定（テーマが front-page.php を自動使用）

### 5. AI記事の自動投稿
GAP-AIO Engine（gap_aio_engine リポジトリ）から WP REST API で投稿:
```bash
python packages/generator/generate.py --client your-clinic-slug
```
config.json に WP認証情報を設定済みであれば、生成記事が自動で投稿されます。

## 4スタイルの特徴

| スタイル | 配色 | 雰囲気 | おすすめ |
|---|---|---|---|
| **medical-trust** | 緑 + 明朝 | 伝統的・信頼感 | 一般患者向け整骨院 |
| **clean-modern** | 青 + ゴシック | モダン・都市型 | ビジネスパーソン向け |
| **warm-care** | オレンジ + 暖色 | 親しみ・温かさ | 産後・女性向け |
| **premium-dark** | 黒 + 金 | 高級感・職人技 | 自費治療・難治性専門 |

## ディレクトリ構成

```
wp-theme/
├── style.css                   ← テーマメタ + 基本CSS
├── functions.php               ← セットアップ・スクリプト読み込み
├── header.php / footer.php
├── front-page.php              ← トップ（10セクション）
├── single.php                  ← 記事ページ
├── index.php                   ← 一覧（カテゴリ・検索）
├── page.php                    ← 固定ページ
├── inc/
│   ├── customizer.php          ← Customizer設定
│   ├── helpers.php             ← データ取得関数
│   └── theme-styles/
│       ├── medical-trust.css
│       ├── clean-modern.css
│       ├── warm-care.css
│       └── premium-dark.css
└── assets/                     ← 画像・JS等
```

## カスタマイズ

### 配色を独自に変更したい
`functions.php` の `gap_aio_get_theme_vars()` で配色を上書きするか、独自CSSを子テーマで追加。

### セクションを増やしたい
`front-page.php` を編集。Customizerフィールドは `inc/customizer.php` で追加。

## サポート
GAP-AIO 受講生サポート窓口: forest-net@xxx.jp（仮）
