<?php // === ADMIN === //

// HTML editor fontstack and fontsize hack; source: http://justintadlock.com/archives/2011/07/06/fixing-wordpress-3-2s-html-editor-font
function ubik_html_editor_fontstack() {
  ?><style type="text/css">
    #wp-content-editor-container textarea#content,
    textarea#wp_mce_fullscreen {
      font-size: <?php echo UBIK_FONTSIZE_EDITOR; ?>;
      font-family: <?php echo UBIK_FONTSTACK_EDITOR; ?>;
    }
  </style><?php
}
add_action( 'admin_head-post.php', 'ubik_html_editor_fontstack' );
add_action( 'admin_head-post-new.php', 'ubik_html_editor_fontstack' );
