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

function view_block_featured_games( $attributes ) {
    if ( ! function_exists( 'wc_get_products' ) ) {
        return '';
    }

    $count = min( 50, max( 1, absint( $attributes['count'] ?? 5 ) ) );
    $products = wc_get_products( array(
        'status'  => 'publish',
        'limit'   => $count,
        'orderby' => 'date',
        'order'   => 'DESC',
    ) );

    $platforms = array(
        'xbox'        => array( 'label' => 'Xbox', 'file' => 'xbox.svg' ),
        'pc'          => array( 'label' => 'Windows', 'file' => 'windows.svg' ),
        'playstation' => array( 'label' => 'PlayStation', 'file' => 'playstation.svg' ),
    );
    $icons_url = plugin_dir_url( __FILE__ ) . 'assets/platforms/';

    ob_start();
    ?>
    <section <?php echo get_block_wrapper_attributes( array( 'class' => 'alignfull' ) ); ?>>
        <div class="featured-games-inner wrapper">
            <?php if ( ! empty( $attributes['title'] ) ) : ?>
                <h2 class="featured-games-title"><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
            <?php endif; ?>
            <?php if ( ! empty( $attributes['description'] ) ) : ?>
                <p class="featured-games-description"><?php echo wp_kses_post( $attributes['description'] ); ?></p>
            <?php endif; ?>
            <?php if ( $products ) : ?>
                <div class="featured-games-grid">
                    <?php foreach ( $products as $product ) : ?>
                        <article class="featured-game">
                            <a class="featured-game-cover" href="<?php echo esc_url( $product->get_permalink() ); ?>" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
                                <?php echo wp_kses_post( $product->get_image( 'large', array( 'alt' => $product->get_name(), 'loading' => 'lazy' ) ) ); ?>
                            </a>
                            <div class="featured-game-price">
                                <?php if ( $product->is_type( 'simple' ) && $product->is_on_sale() && '' !== $product->get_sale_price() ) : ?>
                                    <ins><?php echo wp_kses_post( wc_price( $product->get_sale_price() ) ); ?></ins>
                                    <del><?php echo wp_kses_post( wc_price( $product->get_regular_price() ) ); ?></del>
                                <?php else : ?>
                                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                <?php endif; ?>
                            </div>
                            <h3 class="featured-game-name"><a href="<?php echo esc_url( $product->get_permalink() ); ?>" title="<?php echo esc_attr( $product->get_name() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
                            <div class="featured-game-platforms" aria-label="<?php esc_attr_e( 'Available platforms', 'blocks-gamestore' ); ?>">
                                <?php foreach ( $platforms as $key => $platform ) : ?>
                                    <?php if ( 'yes' === $product->get_meta( '_platform_' . $key ) ) : ?>
                                        <span class="featured-game-platform" title="<?php echo esc_attr( $platform['label'] ); ?>">
                                            <img src="<?php echo esc_url( $icons_url . $platform['file'] ); ?>" alt="<?php echo esc_attr( $platform['label'] ); ?>" width="20" height="20">
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php

    return ob_get_clean();
}
