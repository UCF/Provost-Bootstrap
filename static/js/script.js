// Define globals for JSHint validation:
/* global console */


if (typeof jQuery != 'undefined'){
  jQuery(document).ready(function($) {

    // Call theme-specific functions here.

  });
} else {
  console.log('jQuery dependency failed to load');
}
