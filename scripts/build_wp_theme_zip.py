#!/usr/bin/env python3
"""WordPress テーマ ZIP を packages/wp-theme から生成する。"""
from __future__ import annotations

import os
import zipfile
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
SRC = ROOT / "packages" / "wp-theme"
OUT = ROOT / "packages" / "gap-aio-clinic.zip"


def main() -> None:
    if OUT.exists():
        OUT.unlink()
    count = 0
    with zipfile.ZipFile(OUT, "w", zipfile.ZIP_DEFLATED) as zf:
        for root, _dirs, files in os.walk(SRC):
            for f in files:
                src_path = Path(root) / f
                arc_path = "gap-aio-clinic/" + str(src_path.relative_to(SRC)).replace("\\", "/")
                zf.write(src_path, arc_path)
                count += 1
    size_kb = OUT.stat().st_size / 1024
    print(f"[OK] {OUT.relative_to(ROOT)}  ({count} files, {size_kb:.1f} KB)")


if __name__ == "__main__":
    main()
