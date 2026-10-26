=== GeraCode Barcode ===
Contributors: amandabudri
Tags: barcode, qr code, ean, woocommerce, código de barras
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Código de barras (EAN-13, Code 128, UPC-A, ITF-14) e QR Code por shortcode, e código de barras na página do produto WooCommerce.

== Description ==

Coloque código de barras e QR Code em qualquer página ou post com um shortcode, sem cadastro nem chave de API.

* `[geracode_barcode format="ean13" data="789123456789"]`: EAN-13 (o dígito verificador é calculado se você mandar 12 dígitos).
* `[geracode_barcode format="code128" data="SKU-00042" type="svg"]`: Code 128 em SVG.
* `[geracode_qr data="https://exemplo.com.br" size="300"]`: QR Code.

Formatos: `ean13`, `ean8`, `upca`, `code128`, `code39`, `itf14`. Parâmetros opcionais: `type` (png ou svg), `scale` (1 a 8),
`height` (5 a 50), `text` (1 mostra o número, 0 esconde); no QR Code, `size` (100 a 1000), `margin` (0 a 10) e `ecc` (L, M, Q, H).

**WooCommerce:** em Configurações > GeraCode Barcode, ligue "Mostrar o código de barras na página do produto". O plugin usa o campo
"GTIN, UPC, EAN ou ISBN" do produto (WooCommerce 9.2+); se ele estiver vazio, usa o SKU em Code 128.

QR Code Pix não é gerado pelo plugin.

== External services ==

Este plugin usa a API pública do GeraCode (https://www.geracodigo.com.br) para gerar as imagens. A página do seu site mostra um
`<img>` cujo endereço aponta para `https://www.geracodigo.com.br/api/v1/barcode` ou `https://www.geracodigo.com.br/api/v1/qr`.
Quando um visitante abre a página, o navegador dele pede a imagem a esse serviço, enviando o valor do código (o número, SKU, link
ou texto do QR Code), os parâmetros de tamanho, o endereço IP do visitante e a página de origem (Referer), como em qualquer imagem
externa. O serviço guarda apenas contadores agregados de uso; os contadores por IP, usados para limite de requisições, somem em até 2 dias.

* Termos de uso: https://www.geracodigo.com.br/termos
* Política de privacidade: https://www.geracodigo.com.br/privacidade

== Installation ==

1. Envie a pasta `geracode-barcode` para `/wp-content/plugins/` ou instale pelo painel.
2. Ative o plugin.
3. Use os shortcodes, ou ligue o código de barras do WooCommerce em Configurações > GeraCode Barcode.

== Frequently Asked Questions ==

= Precisa de cadastro ou chave? =

Não. A API é grátis e sem cadastro.

= A imagem tem marca d'água? =

Não. A linha de crédito com link é opcional e vem desligada.

= O código começa com zero e sumiu =

Digite o valor completo no shortcode. No WooCommerce, confira se o campo GTIN guardou o zero da frente.

== Changelog ==

= 1.0.0 =
* Primeira versão: shortcodes `geracode_barcode` e `geracode_qr`, código de barras na página do produto WooCommerce, crédito opcional.
