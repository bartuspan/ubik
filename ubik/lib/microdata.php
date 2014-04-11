<?php // ==== MICRODATA ==== //

// Main wrapper function to generate item properties
function ubik_microdata_wrapper( $content = null, $tag = null, $class = null, $itemprop = null ) {

  if ( empty( $content) )
    return;

  if ( !empty( $class ) )
    $class = ' class="' . $class . '"';

  $microdata = ubik_microdata_itemprop( $itemprop );

  return '<' . $tag . $class . $microdata . '>' . $content . '</' . $tag . '>';
}

// Item property
function ubik_microdata_itemprop( $itemprop ) {
  if ( !empty( $itemprop ) )
    return ' itemprop="' . $itemprop . '"';
  return;
}



// == SHORTCUTS == //

function ubik_microdata_name( $content = null, $tag = 'span', $class = null ) {
  return ubik_microdata_wrapper( $content, $tag, $class, 'name' );
}

function ubik_microdata_description( $content = null, $tag = 'span', $class = null ) {
  return ubik_microdata_wrapper( $content, $tag, $class, 'description' );
}



// == SCOPE == //

// Manage microdata scope
function ubik_microdata_scope( $context = null ) {

  if ( empty( $context ) )
    return;

  switch ( $context ) {
    case 'image-template':
      if ( is_singular() && in_category( 'photography') )
        $scope = ' itemscope itemtype="http://schema.org/Photograph"';
    break;
  }

  $scope = apply_filters( 'pendrell_microdata_scope', $scope );

  echo $scope;
}
