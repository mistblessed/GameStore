<?php

function view_block_games_line($attributes){
    if ( ! function_exists( 'wc_get_products' ) ) {
        return '';
    }

    $products = wc_get_products( array(
        'status'  => 'publish',
        'limit'   => $attributes['count'],
        'orderby' => 'date',
        'order'   => 'DESC',
    ) );

    if ( empty( $products ) ) {
        return '';
    }

    $html = '<div ' . get_block_wrapper_attributes() . '>';
    $html .= '<div class="games-line-container">';
    $html .= '<div class="swiper-wrapper">';

    foreach ( $products as $product ) {
        $html .= '<div class="swiper-slide game-item">';
        $html .= '<a href="' . esc_url( $product->get_permalink() ) . '">';

        $html .= wp_kses_post(
            $product->get_image(
                'full',
                array( 'alt' => $product->get_name() )
            )
        );

        $html .= '</a>';
        $html .= '</div>';
    }

    $html .= '</div>'; // Close swiper-wrapper.
    $html .= '</div>'; // Close games-line-container.
    $html .= '</div>'; // Close the block wrapper.

    return $html;
}