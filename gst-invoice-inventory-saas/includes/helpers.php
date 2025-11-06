<?php
/**
 * Helper utilities.
 *
 * @package GII_SaaS
 */

namespace GII_SaaS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize array of line items.
 *
 * @param array $items Raw items.
 * @return array
 */
function sanitize_line_items( array $items ): array {
	return array_values(
		array_filter(
			array_map(
				static function ( $item ) {
					if ( empty( $item['name'] ) ) {
						return null;
					}
					return [
						'name'     => sanitize_text_field( $item['name'] ?? '' ),
						'price'    => (float) ( $item['price'] ?? 0 ),
						'gst_rate' => (float) ( $item['gst_rate'] ?? 0 ),
					];
				}
				,
				$items
			),
			static function ( $item ) {
				return ! empty( $item );
			}
		)
	);
}

/**
 * Format invoice number.
 *
 * @param int $count Count.
 * @return string
 */
function generate_invoice_number( int $count ): string {
	return sprintf( 'GII-%1$s-%2$05d', gmdate( 'Ymd' ), $count );
}
