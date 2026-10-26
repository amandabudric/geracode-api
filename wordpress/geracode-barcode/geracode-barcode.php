<?php
/**
 * Plugin Name:       GeraCode Barcode
 * Plugin URI:        https://www.geracodigo.com.br/api-codigo-de-barras
 * Description:       Código de barras (EAN-13, Code 128 e outros) e QR Code por shortcode, e código de barras na página do produto WooCommerce.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Amanda Budri
 * Author URI:        https://www.geracodigo.com.br/sobre
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       geracode-barcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const GERACODE_BARCODE_API     = 'https://www.geracodigo.com.br/api/v1';
const GERACODE_BARCODE_DOC     = 'https://www.geracodigo.com.br/api-codigo-de-barras';
const GERACODE_BARCODE_OPTION  = 'geracode_barcode_options';
const GERACODE_BARCODE_FORMATS = array( 'ean13', 'ean8', 'upca', 'code128', 'code39', 'itf14' );

function geracode_barcode_options() {
	return wp_parse_args(
		get_option( GERACODE_BARCODE_OPTION, array() ),
		array(
			'woo_product' => '0',
			'credit'      => '0',
		)
	);
}

/**
 * Monta o <img>. A imagem é gerada pela API do GeraCode a partir da URL; nada é
 * enviado além do valor do código e dos parâmetros de tamanho.
 */
function geracode_barcode_img( $endpoint, array $params, $alt ) {
	$url  = add_query_arg( array_map( 'rawurlencode', $params ), GERACODE_BARCODE_API . '/' . $endpoint );
	$html = sprintf( '<img src="%s" alt="%s" loading="lazy" decoding="async" class="geracode-barcode">', esc_url( $url ), esc_attr( $alt ) );

	// Linha de crédito só se o dono do site ligar nas configurações (desligada por padrão).
	$options = geracode_barcode_options();
	if ( '1' === $options['credit'] ) {
		$html .= sprintf(
			'<br><small>%s <a href="%s">GeraCode</a></small>',
			esc_html__( 'Código gerado com o', 'geracode-barcode' ),
			esc_url( GERACODE_BARCODE_DOC )
		);
	}
	return $html;
}

/**
 * [geracode_barcode format="ean13" data="789123456789" type="png" scale="3" height="15" text="1"]
 */
function geracode_barcode_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'format' => 'ean13',
			'data'   => '',
			'type'   => 'png',
			'scale'  => '',
			'height' => '',
			'text'   => '',
			'alt'    => '',
		),
		$atts,
		'geracode_barcode'
	);
	$data = trim( (string) $atts['data'] );
	if ( '' === $data ) {
		return '';
	}
	$params = array(
		'format' => sanitize_key( $atts['format'] ),
		'type'   => 'svg' === $atts['type'] ? 'svg' : 'png',
	);
	foreach ( array( 'scale', 'height', 'text' ) as $key ) {
		if ( '' !== $atts[ $key ] ) {
			$params[ $key ] = (string) absint( $atts[ $key ] );
		}
	}
	$params['data'] = $data;
	return geracode_barcode_img( 'barcode', $params, '' !== $atts['alt'] ? $atts['alt'] : $data );
}
add_shortcode( 'geracode_barcode', 'geracode_barcode_shortcode' );

/**
 * [geracode_qr data="https://exemplo.com.br" size="300"]
 * QR Code Pix não é gerado pela API.
 */
function geracode_qr_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'data'   => '',
			'type'   => 'png',
			'size'   => '',
			'margin' => '',
			'ecc'    => '',
			'alt'    => '',
		),
		$atts,
		'geracode_qr'
	);
	$data = trim( (string) $atts['data'] );
	if ( '' === $data ) {
		return '';
	}
	$params = array( 'type' => 'svg' === $atts['type'] ? 'svg' : 'png' );
	foreach ( array( 'size', 'margin' ) as $key ) {
		if ( '' !== $atts[ $key ] ) {
			$params[ $key ] = (string) absint( $atts[ $key ] );
		}
	}
	if ( in_array( strtoupper( $atts['ecc'] ), array( 'L', 'M', 'Q', 'H' ), true ) ) {
		$params['ecc'] = strtoupper( $atts['ecc'] );
	}
	$params['data'] = $data;
	return geracode_barcode_img( 'qr', $params, '' !== $atts['alt'] ? $atts['alt'] : 'QR Code' );
}
add_shortcode( 'geracode_qr', 'geracode_qr_shortcode' );

/**
 * WooCommerce: mostra o código de barras do produto no bloco de meta (SKU, categorias).
 * Usa o GTIN do WooCommerce (campo "GTIN, UPC, EAN ou ISBN", WooCommerce 9.2+) como EAN-13/UPC-A/EAN-8/ITF-14
 * conforme o tamanho; sem GTIN, usa o SKU em Code 128.
 */
function geracode_barcode_woo_product() {
	$options = geracode_barcode_options();
	if ( '1' !== $options['woo_product'] ) {
		return;
	}
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$gtin = method_exists( $product, 'get_global_unique_id' ) ? preg_replace( '/\D/', '', (string) $product->get_global_unique_id() ) : '';
	$by_length = array(
		8  => 'ean8',
		12 => 'upca',
		13 => 'ean13',
		14 => 'itf14',
	);
	if ( isset( $by_length[ strlen( $gtin ) ] ) ) {
		$params = array(
			'format' => $by_length[ strlen( $gtin ) ],
			'data'   => $gtin,
		);
	} elseif ( '' !== (string) $product->get_sku() ) {
		$params = array(
			'format' => 'code128',
			'data'   => (string) $product->get_sku(),
		);
	} else {
		return;
	}
	echo '<span class="geracode-barcode-product">' . geracode_barcode_img( 'barcode', $params, $params['data'] ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escapado em geracode_barcode_img().
}
add_action( 'woocommerce_product_meta_end', 'geracode_barcode_woo_product' );

/* Configurações: Configurações > GeraCode Barcode */

function geracode_barcode_register_settings() {
	register_setting(
		'geracode_barcode',
		GERACODE_BARCODE_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'geracode_barcode_sanitize',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'geracode_barcode_register_settings' );

function geracode_barcode_sanitize( $input ) {
	return array(
		'woo_product' => empty( $input['woo_product'] ) ? '0' : '1',
		'credit'      => empty( $input['credit'] ) ? '0' : '1',
	);
}

function geracode_barcode_menu() {
	add_options_page( 'GeraCode Barcode', 'GeraCode Barcode', 'manage_options', 'geracode-barcode', 'geracode_barcode_settings_page' );
}
add_action( 'admin_menu', 'geracode_barcode_menu' );

function geracode_barcode_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$options = geracode_barcode_options();
	?>
	<div class="wrap">
		<h1>GeraCode Barcode</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'geracode_barcode' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'WooCommerce', 'geracode-barcode' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( GERACODE_BARCODE_OPTION ); ?>[woo_product]" value="1" <?php checked( '1', $options['woo_product'] ); ?>>
							<?php esc_html_e( 'Mostrar o código de barras na página do produto (GTIN ou, sem GTIN, o SKU em Code 128)', 'geracode-barcode' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Crédito', 'geracode-barcode' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( GERACODE_BARCODE_OPTION ); ?>[credit]" value="1" <?php checked( '1', $options['credit'] ); ?>>
							<?php esc_html_e( 'Mostrar uma linha "Código gerado com o GeraCode" com link abaixo das imagens (opcional)', 'geracode-barcode' ); ?>
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<h2><?php esc_html_e( 'Shortcodes', 'geracode-barcode' ); ?></h2>
		<p><code>[geracode_barcode format="ean13" data="789123456789"]</code></p>
		<p><code>[geracode_barcode format="code128" data="SKU-00042" type="svg"]</code></p>
		<p><code>[geracode_qr data="https://exemplo.com.br" size="300"]</code></p>
		<p>
			<?php
			printf(
				/* translators: %s: link para a documentação */
				esc_html__( 'Formatos e parâmetros: %s', 'geracode-barcode' ),
				'<a href="' . esc_url( GERACODE_BARCODE_DOC ) . '" target="_blank" rel="noopener">geracodigo.com.br/api-codigo-de-barras</a>'
			);
			?>
		</p>
	</div>
	<?php
}

function geracode_barcode_action_links( $links ) {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=geracode-barcode' ) ) . '">' . esc_html__( 'Configurações', 'geracode-barcode' ) . '</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'geracode_barcode_action_links' );
