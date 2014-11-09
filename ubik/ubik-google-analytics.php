<?php // ==== GOOGLE ANALYTICS ==== //

// Google Analytics code
function ubik_google_analytics() {

  // Legacy support of asynchonous analytics script
  if ( UBIK_GOOGLE_ANALYTICS_ASYNC ) { ?>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '<?php echo UBIK_GOOGLE_ANALYTICS; ?>']);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
  <?php

  // Universal analytics code
  } else { ?>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '<?php echo UBIK_GOOGLE_ANALYTICS; ?>', 'auto');
      <?php if ( UBIK_GOOGLE_ANALYTICS_DISPLAYF ) {
        ?>ga('require', 'displayfeatures');
      <?php }
      ?>ga('send', 'pageview');
    </script>
    <?php
  }
}
add_action( 'wp_footer', 'ubik_google_analytics' );
