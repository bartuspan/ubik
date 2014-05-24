<?php // ==== META ==== //

// These functions are roughly in the order they are invoked
// Filters: ubik_wp_title

// Adds open graph namespace to html tag
function ubik_meta_og_prefix( $atts ) {
  return $atts . ' prefix="og: http://ogp.me/ns#"';
}
add_filter( 'language_attributes', 'ubik_meta_og_prefix' );



// Meta tag generator; loosely modelled after: https://github.com/chuckreynolds/WPFBOGP
function ubik_meta_tags() {

  global $post, $multipage, $wp;

  // Title; Twitter trims this to 70 characters, Facebook seems virtually unlimited
  $title = ubik_content_title();

  // Description; Twitter trims to 200 characters, Facebook likes it long
  $description = ubik_meta_description();

  // Determine the URL
  if ( is_front_page() || is_home() ) {
    $url = trailingslashit( home_url() );
  } else {
    $url = trailingslashit( home_url( add_query_arg( array(), $wp->request) ) );
  }

  // Determine the *canonical* URL; WordPress ordinarily handles is_singular(), let's try the rest; @TODO: support for paged entries and edge cases
  if ( is_archive() ) {
    if ( is_author() ) {
      $canonical = get_author_posts_url( get_the_author_meta( 'ID' ) );
    } elseif ( is_category() || is_tag() || is_tax() ) {
      //$term = get_queried_object();
      $canonical = get_term_link( get_queried_object() );
    } elseif ( is_date() ) {
      if ( is_day() ) {
        $canonical = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
      } elseif ( is_month() ) {
        $canonical = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
      } elseif ( is_year() ) {
        $canonical = get_year_link( get_query_var( 'year' ) );
      }
    }
  } elseif ( is_front_page() || is_home() ) {
    $canonical = $url;
  } elseif ( is_post_type_archive() ) {
    // @TODO: add something here?
  } elseif ( is_search() ) {
    $canonical = get_search_link();
  }

  // Image handling
  $images = array();
  if ( UBIK_META_IMAGE_SIZE ) {
    $image_size = UBIK_META_IMAGE_SIZE;
  } else {
    $image_size = 'large';
  }
  $image_size = apply_filters( 'ubik_meta_image_size', $image_size );
  if ( is_singular() ) {

    // Are we are dealing with an image attachment page?
    if ( is_attachment() && wp_attachment_is_image() ) {

      // Get all metadata (includes mime type) and image source in the usual way
      $attachment = wp_get_attachment_metadata( $post->ID );
      $image_src = wp_get_attachment_image_src( $post->ID, $image_size );

      // Is there an attachment of the desired size? Fill the mime type and appropriate height/width info
      if ( !empty( $attachment['sizes'][$image_size] ) ) {
        $images[] = array(
          'url' => $image_src[0],
          'type' => $attachment['sizes'][$image_size]['mime-type'],
          'width' => $attachment['sizes'][$image_size]['width'],
          'height' => $attachment['sizes'][$image_size]['height']
        );

      // Otherwise fallback to the default image size and no mime type
      } else {
        $images[] = array(
          'url' => $image_src[0],
          'width' => $image_src[1],
          'height' => $image_src[2]
        );
      }

    // All other posts, pages, etc.
    } else {

      // Featured images should be first
      if ( has_post_thumbnail( $post->ID ) ) {
        $image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $image_size );
        $images[] = array(
          'url' => $image_src[0],
          'width' => $image_src[1],
          'height' => $image_src[2]
        );
      }

      // Fetch additional images here but be sure not to duplicate the featured image
      $attachments = get_attached_media( 'image' );
      if ( count( $attachments ) > 1 ) {
        foreach ( $attachments as $attachment ) {
          $image_src = wp_get_attachment_image_src( $attachment->ID, $image_size );

          // Don't duplicate featured image
          if ( has_post_thumbnail( $post->ID ) ) {
            if ( $image_src[0] !== $images[0]['url'] ) {
              $images[] = array(
                'url' => $image_src[0],
                'width' => $image_src[1],
                'height' => $image_src[2]
              );
            }
          } else {
            $images[] = array(
              'url' => $image_src[0],
              'width' => $image_src[1],
              'height' => $image_src[2]
            );
          }
        }
      }
    }

  // Everything that isn't singular is handled next
  // @TODO: images for other forms of content e.g. author profile image, category thumbnail, or whatever else
  } else {
    //
  }

  // Additional post-specific data
  if ( is_singular() ) {

    // Author
    $author = get_the_author_meta( 'display_name', $post->post_author );
    $author_facebook = get_the_author_meta( 'facebook', $post->post_author );
    $author_google = get_the_author_meta( 'google', $post->post_author );
    $author_twitter = get_the_author_meta( 'twitter', $post->post_author );

    // Category and tags
    $category = get_the_category();
    $tags = get_the_tags();
  }



  // == GENERAL == //

  // Output canonical URL if it exists
  if ( !empty( $canonical ) )
    echo '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . "\n";

  // Old school meta description
  if ( !empty( $description ) )
    echo '<meta name="description" content="' . esc_attr( $description ) . '"/>' . "\n";



  // == FACEBOOK == //

  // Open Graph administration
  if ( UBIK_META_FACEBOOK_ADMINS )
    echo '<meta property="fb:admins" content="' . UBIK_META_FACEBOOK_ADMINS . '"/>' . "\n";

  // Open Graph title
  echo '<meta property="og:title" content="' . esc_attr( $title ) . '"/>' . "\n";

  // Open Graph URL; @TODO: set to $canonical wherever relevant
  echo '<meta property="og:url" content="' . esc_url( $url ) . '"/>' . "\n";

  // Open graph type
  if ( is_singular() && !is_attachment() ) {
    $type = 'article';
  } else {
    $type = 'website';
  }
  echo '<meta property="og:type" content="' . esc_attr( $type ) . '"/>' . "\n";

  // Open Graph images; Pinterest allows up to 6
  if ( !empty( $images ) ) {
    foreach ( $images as $image ) {
      echo '<meta property="og:image" content="' . esc_url( $image['url'] ) . '"/>' . "\n";
      if ( !empty( $image['type'] ) )
        echo '<meta property="og:image:type" content="' . esc_attr( $image['type'] ) . '"/>' . "\n";
      if ( !empty( $image['width'] ) )
        echo '<meta property="og:image:width" content="' . esc_attr( $image['width'] ) . '"/>' . "\n";
      if ( !empty( $image['height'] ) )
        echo '<meta property="og:image:height" content="' . esc_attr( $image['height'] ) . '"/>' . "\n";
    }
  }

  // Optional content-specific Open Graph tags
  if ( !empty( $description ) )
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '"/>' . "\n";

  // Open Graph site name
  echo '<meta property="og:site_name" content="' . get_bloginfo( 'name' ) . '"/>' . "\n";

  // Open Graph locale; refer to Yoast for direction on locale exceptions: https://github.com/Yoast/wordpress-seo
  echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '" />' . "\n";

  // Open Graph article tags
  if ( $type === 'article' ) {

    // Date and time
    echo '<meta property="article:published_time" content="' . get_post_time( 'c', true ) . '"/>' . "\n";
    echo '<meta property="article:modified_time" content="' . get_post_modified_time( 'c', true ) . '"/>' . "\n";

    // Article author; Facebook and Pinterest use this property in mutually incompatible ways
    // To address the conflict we will test for the Pinterest crawler user agent and serve the plaintext author name
    // Note: this requires that caching is disabled for this user agent!
    if ( stripos( $_SERVER['HTTP_USER_AGENT'], 'Pinterest' ) !== false ) {
      echo '<meta property="article:author" content="' . esc_attr( $author ) . '"/>' . "\n";
    } elseif ( !empty( $author_facebook ) ) {
      echo '<meta property="article:author" content="http://www.facebook.com/' . esc_attr( $author_facebook ) . '"/>' . "\n";
    }

    // Publisher; must be a Facebook Page
    if ( UBIK_META_FACEBOOK_PUBLISHER )
      echo '<meta property="article:publisher" content="http://www.facebook.com/' . UBIK_META_FACEBOOK_PUBLISHER . '"/>' . "\n";

    // Category, but only one... @TODO: custom usort() function to select category by count or something
    if ( !empty( $category ) )
      echo '<meta property="article:section" content="' . esc_attr( $category[0]->cat_name ) . '"/>' . "\n";

    // Tags, as many as you like...
    if ( !empty( $tags ) ) {
      foreach ( $tags as $tag ) {
        echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '"/>' . "\n";
      }
    }
  }



  // == TWITTER == //

  // Twitter title, description, and URL meta tags are not required when equivalent Open Graph tags exist!
  if ( is_attachment() && wp_attachment_is_image() && !empty( $images ) ) {
    $card = 'photo';
  } elseif ( is_singular() && !is_attachment() && !empty( $images ) && count( $images ) > 2 ) {
    $card = 'gallery';
  } elseif ( is_singular() && !is_attachment() && !empty( $images ) && $images[0]['width'] >= 280 && $images[0]['height'] >= 150) {
    // Width and height checks ensure image complies with Twitter requirements
    $card = 'summary_large_image';
  } else {
    $card = 'summary';
  }
  echo '<meta name="twitter:card" content="' . $card . '">' . "\n";

  // Twitter card author; article-specific as well as site-wide, if defined
  if ( !empty( $author_twitter ) )
    echo '<meta name="twitter:creator" content="@' . get_the_author_meta( 'twitter', $post->post_author ) . '">' . "\n";
  if ( UBIK_META_TWITTER_PUBLISHER )
    echo '<meta name="twitter:site" content="@' . UBIK_META_TWITTER_PUBLISHER . '">' . "\n";

  // Twitter card images; we could fallback on Open Graph images but there are some slight differences here
  // @TODO: check to ensure all images are less than 1 Mb
  if ( !empty( $images ) ) {

    // Twitter gallery cards only handle up to 4 images
    if ( $card === 'gallery' ) {
      for( $i = 0; $i < 4; ++$i) {
        if ( $images[$i]['url'] )
          echo '<meta property="twitter:image' . $i . ':src" content="' . esc_url( $images[$i]['url'] ) . '"/>' . "\n";
        if ( $images[$i]['width'] )
          echo '<meta property="twitter:image' . $i . ':width" content="' . esc_attr( $images[$i]['width'] ) . '"/>' . "\n";
        if ( $images[$i]['height'] )
          echo '<meta property="twitter:image' . $i . ':height" content="' . esc_attr( $images[$i]['height'] ) . '"/>' . "\n";
      }

    // A single image for photo, summary, and summary_large_image cards
    } else {
      echo '<meta property="twitter:image:src" content="' . esc_url( $images[0]['url'] ) . '"/>' . "\n";
      if ( $images[0]['width'] )
        echo '<meta property="twitter:image:width" content="' . esc_attr( $images[0]['width'] ) . '"/>' . "\n";
      if ( $images[0]['height'] )
        echo '<meta property="twitter:image:height" content="' . esc_attr( $images[0]['height'] ) . '"/>' . "\n";
    }
  }



  // == GOOGLE == //

  // Google Plus author and publisher
  if ( !empty( $author_google ) )
    echo '<link rel="author" href="https://google.com/+' . $author_google . '">' . "\n";
  if ( UBIK_META_GOOGLE_PUBLISHER )
    echo '<link rel="publisher" href="https://plus.google.com/' . UBIK_META_GOOGLE_PUBLISHER . '">' . "\n";

}
add_action( 'wp_head', 'ubik_meta_tags' );



// Generate a plaintext meta description
function ubik_meta_description() {
  global $post;

  // Single posts, pages, and attachments
  if ( is_singular() )
    return get_the_excerpt();

  // No excerpt
  if ( is_404() || is_search() )
    return '';

  // Now match other possibilities...
  if ( is_author() ) {
    $description = get_the_author_meta( 'description' );
  }

  // Check to see if we have a description for this category, tag, or taxonomy
  if ( is_category() || is_tag() || is_tax() ) {
    $description = term_description();
  }

  // Front or home page
  if ( is_front_page() || is_home() )
    $description = get_bloginfo( 'description' );

  if ( UBIK_EXCERPT )
    $description = ubik_excerpt_sanitize( $description );

  // Returns a description (if one was found); be sure to handle empty/blank descriptions anywhere this is used
  return $description;
}



// Generate favicon markup; generate all necessary files with http://realfavicongenerator.net/
function ubik_meta_favicons() {

  // Provide an option for Windows 8 tile colour to be specified; default to black
  if ( UBIK_META_FAVICONS_TILE_COLOR ) {
    $favicons_tile_color = UBIK_META_FAVICONS_TILE_COLOR;
  } else {
    $favicons_tile_color = "#000000";
  }

  $favicons = '';
  $favicons .= '<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">' . "\n";
  $favicons .= '<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">' . "\n";
  $favicons .= '<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">' . "\n";
  $favicons .= '<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">' . "\n";
  $favicons .= '<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">' . "\n";
  $favicons .= '<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">' . "\n";
  $favicons .= '<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">' . "\n";
  $favicons .= '<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">' . "\n";
  $favicons .= '<link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196">' . "\n";
  $favicons .= '<link rel="icon" type="image/png" href="/favicon-160x160.png" sizes="160x160">' . "\n";
  $favicons .= '<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">' . "\n";
  $favicons .= '<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">' . "\n";
  $favicons .= '<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">' . "\n";
  $favicons .= '<meta name="msapplication-TileColor" content="' . $favicons_tile_color . '">' . "\n";
  $favicons .= '<meta name="msapplication-TileImage" content="/mstile-144x144.png">' . "\n";

  echo $favicons;
}
if ( UBIK_META_FAVICONS )
  add_action( 'wp_head', 'ubik_meta_favicons' );
