#!/usr/bin/env python3
"""全クライアントをループして、各院で1記事ずつ生成する。

GitHub Actions の毎日 cron から呼ばれる。
"""
from __future__ import annotations

import json
import subprocess
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent.parent
CLIENTS_DIR = ROOT / "clients"
GENERATOR = ROOT / "packages" / "generator" / "generate.py"


def list_clients() -> list[str]:
    if not CLIENTS_DIR.exists():
        return []
    return sorted(
        d.name for d in CLIENTS_DIR.iterdir()
        if d.is_dir() and (d / "config.json").exists()
    )


def main() -> None:
    clients = list_clients()
    if not clients:
        print("[Orchestrator] No clients found.")
        return

    print(f"[Orchestrator] Found {len(clients)} client(s): {clients}")
    successes = []
    failures = []
    for slug in clients:
        print(f"\n=== Generating for: {slug} ===", flush=True)
        try:
            result = subprocess.run(
                [sys.executable, str(GENERATOR), "--client", slug],
                capture_output=True,
                text=True,
                encoding="utf-8",
                timeout=300,
            )
            if result.returncode == 0:
                successes.append(slug)
                print(result.stdout)
            else:
                failures.append((slug, result.stderr or result.stdout))
                print(f"[FAIL] {slug}: {result.stderr[:300]}", file=sys.stderr)
        except subprocess.TimeoutExpired:
            failures.append((slug, "timeout"))
            print(f"[TIMEOUT] {slug}")
        except Exception as e:
            failures.append((slug, str(e)))
            print(f"[ERROR] {slug}: {e}", file=sys.stderr)

    print("\n=== Summary ===")
    print(f"  Success: {len(successes)}/{len(clients)} → {successes}")
    if failures:
        print(f"  Failures: {len(failures)}")
        for slug, err in failures:
            print(f"    - {slug}: {err[:200]}")
        sys.exit(1)


if __name__ == "__main__":
    main()
