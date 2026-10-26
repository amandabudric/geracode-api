# Código de barras e QR Code no Google Planilhas e no Excel

Coloque o código na coluna A (a partir de A2) e a fórmula na coluna B. Arraste para baixo.

**Formate a coluna A como texto** antes de colar códigos que começam com zero, senão a planilha apaga o zero da frente.

## Google Planilhas

Use os nomes em inglês (`IMAGE` e `ENCODEURL`), mesmo com a planilha em português.

| Formato | Fórmula |
|---|---|
| EAN-13 | `=IMAGE("https://www.geracodigo.com.br/api/v1/barcode?format=ean13&data="&A2)` |
| Code 128 | `=IMAGE("https://www.geracodigo.com.br/api/v1/barcode?format=code128&data="&ENCODEURL(A2))` |
| QR Code | `=IMAGE("https://www.geracodigo.com.br/api/v1/qr?data="&ENCODEURL(A2))` (não verificada no Planilhas) |

Na primeira vez, o Planilhas mostra a faixa amarela *"Aviso: algumas fórmulas estão tentando enviar e receber dados de terceiros."*
Clique em **Permitir acesso**. Até liberar, a célula mostra `#REF!`.

## Modelo pronto

[`modelo-codigo-de-barras.xlsx`](modelo-codigo-de-barras.xlsx): abas de EAN-13, Code 128 e QR Code com as fórmulas até a linha 51 e a coluna A já
formatada como texto. No Google Planilhas: **Arquivo > Importar > Fazer upload**. A aba "Sobre" explica o passo a passo.

## Excel 365 (Windows)

No Excel em português os nomes são traduzidos: `IMAGEM` e `CODIFURL`.

| Formato | Fórmula |
|---|---|
| EAN-13 | `=IMAGEM("https://www.geracodigo.com.br/api/v1/barcode?format=ean13&data="&A2)` |
| Code 128 | `=IMAGEM("https://www.geracodigo.com.br/api/v1/barcode?format=code128&data="&CODIFURL(A2))` |
| QR Code | `=IMAGEM("https://www.geracodigo.com.br/api/v1/qr?data="&CODIFURL(A2))` |

`CODIFURL` só existe no Excel para Windows. Mac e web não foram testados.

Mais formatos e parâmetros: https://www.geracodigo.com.br/api-codigo-de-barras
