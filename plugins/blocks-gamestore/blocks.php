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

function view_block_recent_news( $attributes, $content, $block ) {
    $news_query = new WP_Query( array(
        'post_type'      => 'news',
        'post_status'    => 'publish',
        'posts_per_page' => max( 1, absint( $attributes['count'] ?? 3 ) ),
        'orderby'       => 'date',
        'order'         => 'DESC',
        'no_found_rows'  => true,
    ) );

    $wrapper_attributes = array();
    if ( ! empty( $attributes['image'] ) ) {
        $wrapper_attributes['style'] = 'background-image: url("' . esc_url( $attributes['image'] ) . '");';
    }

    ob_start();
    ?>
    <div <?php echo get_block_wrapper_attributes( $wrapper_attributes ); ?>>
        <?php if ( $news_query->have_posts() ) : ?>
            <?php if ( ! empty( $attributes['title'] ) ) : ?>
                <h2><?php echo esc_html( $attributes['title'] ); ?></h2>
            <?php endif; ?>
            <?php if ( ! empty( $attributes['description'] ) ) : ?>
                <p><?php echo esc_html( $attributes['description'] ); ?></p>
            <?php endif; ?>
            <div class="recent-news wrapper">
            <?php while ( $news_query->have_posts() ) : ?>
                <?php $news_query->the_post(); ?>

                <article class="news-item">
                    <?php if (has_post_thumbnail()):?>
                    <h3><?php echo esc_html( get_the_title() ); ?></h3>
                    <div class="news-thumbnail">
                        <img src="<?php echo get_the_post_thumbnail_url() ?>" class="blur-image"  alt="<?php get_the_title() ?>">
                        <img src="<?php echo get_the_post_thumbnail_url() ?>" class="original-image"  alt="<?php get_the_title() ?>">
                    </div>
                    <div class="news-excerpt">
                    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                    </div>
                    <a href="<?php echo esc_url(get_permalink()) ?>" class="read-more" >Open The Post</a>
                    <?php endif; ?>
                </article>

            <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p>No news yet.</p>
        <?php endif; ?>
    </div>
    <?php

    wp_reset_postdata();

    return ob_get_clean();
}

function view_block_subscribe($attributes){
    $image_bg = ($attributes['image']) ? 'style="background-image: url(' . $attributes['image'] . ')"' : '';

    ob_start();
    echo '<div ' . get_block_wrapper_attributes(array('class' => 'alignfull')) . $image_bg . '>';
    echo '<div class="subscribe-inner wrapper">';
        echo '<h2 class="subscribe-title">'.$attributes['title'].'</h2>';
        echo '<p class="subscribe-description">'.$attributes['description'].'</p>';
        echo '<div class="subscribe-shortcode">'.do_shortcode($attributes['shortcode']).'</div>';    
    echo '</div>';
    echo '</div>';

    return ob_get_clean();
}