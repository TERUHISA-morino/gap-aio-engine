#!/usr/bin/env python3
"""新規クライアント院をオンボードする CLI。

Usage:
  python scripts/onboard.py \\
      --slug clinic-001 \\
      --name "○○整骨院" \\
      --domain "https://blog.example.jp" \\
      --specialties "腰痛,肩こり,膝痛"
"""
from __future__ import annotations

import argparse
import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
CLIENTS_DIR = ROOT / "clients"

DEFAULT_TOPICS = [
    "慢性的な腰痛は、なぜマッサージで治らないのか",
    "肩こりの本当の原因と、根本治療への道筋",
    "ぎっくり腰を繰り返す人の身体的特徴",
    "四十肩・五十肩の正体と、再発を防ぐ考え方",
    "頭痛は脳の問題ではない ―― 物理的アライメント異常という視点",
    "膝痛が改善しない人が見落としている重力バランス",
    "自律神経の乱れと、関節構造の関係",
    "姿勢矯正だけでは治らない、本当の理由",
    "デスクワークの肩こりと胸郭の関係",
    "歩行時のふらつきは、重力感知点のサインかもしれない",
    "立ちっぱなしで腰が痛む人の身体的特徴",
    "猫背は背中の問題ではない ―― 全身の重力バランスから読み解く",
    "むちうち後の慢性症状と関節機能",
    "原因不明の倦怠感と、身体の物理的アライメント",
    "顎関節症と、全身の重力バランスの関係",
    "夜間の腰痛は、なぜ起こるのか",
    "肩甲骨の動きが悪い人が抱える本当の問題",
    "首の寝違えを繰り返さないために必要な視点",
    "腰の反り過ぎ・反らない、それぞれの身体的意味",
    "呼吸が浅い人の身体的特徴と、改善の糸口",
    "なぜ「気持ちいい治療」では根本的に治らないのか",
    "GAP理論とは何か ―― 重力感知点の概念",
    "産後の腰痛と仙腸関節 ―― 出産で変わる身体構造",
    "高齢者の転倒リスクと、関節構造の重要性",
    "更年期以降の身体の不調と、関節アプローチ",
    "成長期の子どもの姿勢が悪い、親が知っておくべきこと",
    "便秘と内臓下垂、その背景にある身体構造",
    "歩く時の膝の音は、何を意味しているのか",
    "スポーツ選手のパフォーマンス低下と重力感知点",
    "股関節の違和感と、全身の連動性",
]


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--slug", required=True, help="ローマ字 + ハイフンの一意ID")
    parser.add_argument("--name", required=True, help="院名（日本語）")
    parser.add_argument("--domain", required=True, help="公開ドメイン (例: https://blog.example.jp)")
    parser.add_argument("--specialties", required=True, help="カンマ区切り (例: '腰痛,肩こり,膝痛')")
    parser.add_argument("--address", default="", help="所在地（フッターに表示）")
    parser.add_argument("--contact", default="", help="来院相談ページのURL")
    parser.add_argument("--topics", type=int, default=30, help="初期トピック数（デフォルト30）")
    args = parser.parse_args()

    target_dir = CLIENTS_DIR / args.slug
    if target_dir.exists():
        sys.exit(f"ERROR: client already exists: {target_dir}")

    specialties = [s.strip() for s in args.specialties.split(",") if s.strip()]

    # config.json
    config = {
        "slug": args.slug,
        "name": args.name,
        "tagline": f"GAP理論に基づく根本治療を提供する{specialties[0] if specialties else '整体'}専門院。",
        "author": args.name,
        "domain": args.domain,
        "address": args.address,
        "contactUrl": args.contact,
        "specialties": specialties,
        "vercel_project_id": "",
        "monthly_articles": 30,
        "schedule": "0 20 * * *",
    }

    target_dir.mkdir(parents=True)
    (target_dir / "config.json").write_text(
        json.dumps(config, ensure_ascii=False, indent=2),
        encoding="utf-8",
    )

    # topics.json
    topics = [{"title": t, "category": "症状解説"} for t in DEFAULT_TOPICS[: args.topics]]
    (target_dir / "topics.json").write_text(
        json.dumps(topics, ensure_ascii=False, indent=2),
        encoding="utf-8",
    )

    # articles/ ディレクトリ
    (target_dir / "articles").mkdir()

    print(f"[OK] client created: {target_dir.relative_to(ROOT)}")
    print(f"     topics seeded: {len(topics)}")
    print()
    print("次のステップ:")
    print(f"  1. Vercel で新規プロジェクト作成 → リポジトリと連携 → root: packages/astro-template")
    print(f"  2. Vercel 環境変数: CLIENT_SLUG={args.slug}")
    print(f"  3. ドメイン {args.domain} を Vercel プロジェクトに紐付け")
    print(f"  4. 初回記事生成テスト:")
    print(f"     python packages/generator/generate.py --client {args.slug}")


if __name__ == "__main__":
    main()
