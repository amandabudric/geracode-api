"""Baixa um Code 128 e um QR Code em PNG. Só biblioteca padrão.

Uso: python3 gerar.py
"""
import json
import urllib.error
import urllib.parse
import urllib.request

API = "https://www.geracodigo.com.br/api/v1"


def baixar(caminho: str, params: dict, arquivo: str) -> None:
    url = f"{API}/{caminho}?{urllib.parse.urlencode(params)}"
    try:
        with urllib.request.urlopen(url) as res, open(arquivo, "wb") as f:
            f.write(res.read())
        print("salvo", arquivo)
    except urllib.error.HTTPError as e:
        # Erro vem em JSON: {"erro": "...", "dica": "..."}
        corpo = json.loads(e.read())
        raise SystemExit(f"{corpo.get('erro')} {corpo.get('dica', '')}".strip())


baixar("barcode", {"format": "code128", "data": "SKU-00042"}, "code128.png")
baixar("qr", {"data": "https://exemplo.com.br", "size": 400}, "qrcode.png")
