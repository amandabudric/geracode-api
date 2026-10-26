# API de código de barras e QR Code do GeraCode: exemplos

Exemplos de uso da [API grátis do GeraCode](https://www.geracodigo.com.br/api-codigo-de-barras), que devolve
imagens PNG ou SVG de código de barras (EAN-13, EAN-8, UPC-A, Code 128, Code 39, ITF-14) e QR Code a partir de uma URL.
Sem cadastro, sem chave, sem marca d'água.

```
https://www.geracodigo.com.br/api/v1/barcode?format=ean13&data=789123456789
https://www.geracodigo.com.br/api/v1/qr?data=https%3A%2F%2Fexemplo.com.br
```

| Pasta | O que tem |
|---|---|
| [`exemplos/planilhas`](exemplos/planilhas) | Fórmulas para Google Planilhas e Excel, e modelo pronto para copiar |
| [`exemplos/html`](exemplos/html) | `<img>` direto na página |
| [`exemplos/javascript`](exemplos/javascript) | Node 18+, baixa o PNG com `fetch` |
| [`exemplos/python`](exemplos/python) | Só biblioteca padrão |
| [`exemplos/php`](exemplos/php) | Monta a URL e salva o PNG |
| [`wordpress/geracode-barcode`](wordpress/geracode-barcode) | Plugin WordPress com shortcodes e código de barras na página do produto WooCommerce |

## Parâmetros

**`/api/v1/barcode`**: `format` (`ean13`, `ean8`, `upca`, `code128`, `code39`, `itf14`; aceita também `gtin13`, `dun14`, `upc`),
`data` (obrigatório), `type` (`png` ou `svg`), `scale` (1–8), `height` (5–50), `text` (`1` mostra o número, `0` esconde).
EAN, UPC e ITF-14 podem ir sem o dígito verificador: a API calcula.

**`/api/v1/qr`**: `data` (obrigatório, até 1.200 caracteres), `type`, `size` (100–1000 px), `margin` (0–10), `ecc` (`L`, `M`, `Q`, `H`).
QR Code Pix não é gerado pela API; use o [gerador de Pix do site](https://www.geracodigo.com.br/gerador-de-qr-code-pix).

Erros voltam com status 400 e JSON `{"erro": "...", "dica": "..."}`. Imagens ficam em cache por 1 ano, então a mesma URL é instantânea
da segunda vez em diante. Limites e detalhes na [documentação](https://www.geracodigo.com.br/api-codigo-de-barras).

## Crédito

Não é obrigatório. Se a API te ajudou, um link de volta ajuda o projeto a continuar grátis:

```html
Código gerado com a <a href="https://www.geracodigo.com.br/api-codigo-de-barras">API grátis do GeraCode</a>
```

## English

Free barcode and QR code image API: build a URL, get a PNG or SVG. No sign-up, no key, no watermark.
Docs are in Portuguese at [geracodigo.com.br/api-codigo-de-barras](https://www.geracodigo.com.br/api-codigo-de-barras);
the parameters above are the same in any language. Examples in this repo are MIT licensed.
