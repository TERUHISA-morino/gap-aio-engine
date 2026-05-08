#!/usr/bin/env python3
"""1記事を生成して clients/{slug}/articles/ に保存する。

Gemini REST API を直接叩くシンプルな実装（gRPC SSL問題回避）。

Usage:
  python packages/generator/generate.py --client gap-medical-seitai
  python packages/generator/generate.py --client gap-medical-seitai --topic "肩こり"
"""
from __future__ import annotations

import argparse
import json
import os
import re
import sys
from datetime import date
from pathlib import Path

import requests
import urllib3

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

ROOT = Path(__file__).resolve().parent.parent.parent
SHARED_PROMPT = (ROOT / "shared" / "gap-knowledge" / "base-prompt.md").read_text(encoding="utf-8")

VERIFY_SSL = os.environ.get("GEMINI_SSL_VERIFY", "0") == "1"


def slugify(text: str) -> str:
    """日本語タイトルを安全なファイル名にする"""
    text = text.lower()
    text = re.sub(r"[^\w一-鿿぀-ヿ\-]+", "-", text)
    text = re.sub(r"-+", "-", text).strip("-")
    return text[:50] if text else "article"


def load_client(slug: str) -> dict:
    config_path = ROOT / "clients" / slug / "config.json"
    if not config_path.exists():
        sys.exit(f"ERROR: client config not found: {config_path}")
    return json.loads(config_path.read_text(encoding="utf-8"))


def load_topics(slug: str) -> list[dict]:
    topics_path = ROOT / "clients" / slug / "topics.json"
    if not topics_path.exists():
        return []
    return json.loads(topics_path.read_text(encoding="utf-8"))


def save_topics(slug: str, topics: list[dict]) -> None:
    path = ROOT / "clients" / slug / "topics.json"
    path.write_text(json.dumps(topics, ensure_ascii=False, indent=2), encoding="utf-8")


def existing_article_topics(slug: str) -> set[str]:
    articles_dir = ROOT / "clients" / slug / "articles"
    if not articles_dir.exists():
        return set()
    titles = set()
    for f in articles_dir.glob("*.md"):
        text = f.read_text(encoding="utf-8")
        m = re.search(r'^title:\s*"([^"]+)"', text, re.MULTILINE)
        if m:
            titles.add(m.group(1))
    return titles


def build_user_prompt(client: dict, topic: str) -> str:
    return f"""以下の指示に従って、症状解説記事を1本生成してください。

## クライアント院情報
- 院名: {client.get('name', '○○整体院')}
- キャッチコピー: {client.get('tagline', 'GAP理論実践院')}
- 専門分野: {", ".join(client.get('specialties', []))}
- 所在地: {client.get('address', '東京都内')}

## 今回の記事テーマ
**{topic}**

## 出力フォーマット（厳守）

```
---
title: "（記事タイトル）"
date: "{date.today().isoformat()}"
category: "症状解説"
excerpt: "（120〜180文字の要約。検索結果に表示される）"
faq:
  - ["（よくある質問1）", "（その回答 80〜120文字）"]
  - ["（よくある質問2）", "（その回答 80〜120文字）"]
  - ["（よくある質問3）", "（その回答 80〜120文字）"]
---

（本文 H2 セクション 3〜5個、各 300〜600 文字、計 2,000〜3,000 文字）
```

frontmatter の faq 配列は schema.org FAQPage として AI 検索に引用されやすくするため必須です。

## 内容に含めるべき要素

1. **症状の通説を否定**して始める（「○○は××ではない」のような切り口）
2. **GAP理論の物理メカニズム**で本当の原因を説明（重力感知点・関節構造・物理アプローチを使う）
3. **一般治療法の限界**を論理的に説明（マッサージや湿布が根本解決にならない理由）
4. **GAP理論に基づくアプローチ**を提示（具体的な手技や部位は避け、「専門評価が必要」と書く）
5. **来院相談への自然な誘導**で締める

## 重要な制約

- 「治る」「完治」「即効」などの**断定的な表現は禁止**
- GAP②③ の具体的な部位を**推測で書かない**
- **「胸鎖関節」は使わない**（GAP理論で中心概念ではない）
- 文字数は本文のみで2,000〜3,000文字を厳守
- 自院は「当院」、対象は「患者さま」を基本に

それでは、上記フォーマットで記事を出力してください。フォーマット以外の前置き・後書きは不要です。
"""


def generate_article(client: dict, topic: str, model_name: str = "gemini-2.5-flash") -> str:
    api_key = os.environ.get("GEMINI_API_KEY")
    if not api_key:
        sys.exit("ERROR: GEMINI_API_KEY env var required")

    url = f"https://generativelanguage.googleapis.com/v1beta/models/{model_name}:generateContent?key={api_key}"
    payload = {
        "system_instruction": {"parts": [{"text": SHARED_PROMPT}]},
        "contents": [{"parts": [{"text": build_user_prompt(client, topic)}]}],
        "generationConfig": {
            "temperature": 0.7,
            "topP": 0.95,
            "maxOutputTokens": 8192,
        },
    }
    r = requests.post(url, json=payload, timeout=180, verify=VERIFY_SSL,
                      headers={"User-Agent": "gap-aio-engine/0.1"})
    if r.status_code != 200:
        sys.exit(f"ERROR Gemini API {r.status_code}: {r.text[:600]}")
    data = r.json()
    candidates = data.get("candidates", [])
    if not candidates:
        sys.exit(f"ERROR: no candidates in response: {data}")
    parts = candidates[0].get("content", {}).get("parts", [])
    if not parts:
        sys.exit(f"ERROR: no content parts: {data}")
    text = parts[0].get("text", "").strip()
    # ```で囲まれていたら除去
    text = re.sub(r"^```(?:markdown|md)?\n", "", text)
    text = re.sub(r"\n```\s*$", "", text)
    return text.strip()


def parse_title(md_text: str) -> str:
    m = re.search(r'^title:\s*"([^"]+)"', md_text, re.MULTILINE)
    return m.group(1) if m else "untitled"


def save_article(slug: str, md_text: str) -> Path:
    articles_dir = ROOT / "clients" / slug / "articles"
    articles_dir.mkdir(parents=True, exist_ok=True)
    title = parse_title(md_text)
    filename = f"{date.today().isoformat()}-{slugify(title)}.md"
    target = articles_dir / filename
    counter = 1
    while target.exists():
        target = articles_dir / f"{date.today().isoformat()}-{slugify(title)}-{counter}.md"
        counter += 1
    target.write_text(md_text + "\n", encoding="utf-8")
    return target


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--client", required=True)
    parser.add_argument("--topic")
    parser.add_argument("--model", default="gemini-2.5-flash")
    args = parser.parse_args()

    client = load_client(args.client)
    topics = load_topics(args.client)
    written = existing_article_topics(args.client)

    if args.topic:
        next_topic = args.topic
    else:
        pending = [t for t in topics if not t.get("done") and t.get("title") not in written]
        if not pending:
            sys.exit("ERROR: no pending topics for client")
        next_topic = pending[0].get("title")

    print(f"[Generator] client={args.client}, topic={next_topic}", flush=True)
    md = generate_article(client, next_topic, model_name=args.model)
    target = save_article(args.client, md)
    print(f"[OK] saved: {target.relative_to(ROOT)}", flush=True)
    print(f"     title: {parse_title(md)}", flush=True)
    print(f"     bytes: {target.stat().st_size:,}", flush=True)

    if not args.topic:
        for t in topics:
            if t.get("title") == next_topic:
                t["done"] = True
                t["written_at"] = date.today().isoformat()
                break
        save_topics(args.client, topics)


if __name__ == "__main__":
    main()
