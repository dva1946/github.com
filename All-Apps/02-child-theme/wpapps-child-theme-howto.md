Date: 10-06-22
Filename: wpapps-child-theme-howto.md
Purpose: Setup child theme

DIY Child Theme:
    1. This is not my first theme rodeo!
    2. With WP 6.x and PHP 7.x plugins generally failed.
    3. DIY to the rescue.
    4. Use the KISS process!

DIY Setup:
    1. Create a child theme directory at same level as parent theme.
       Directory filename: parentthemename-child
       Example: twentytwentytwo-child.

    3. Copy files to new child theme directory:
       functions.php
       wp-apps-style.css
    
    3. From wp-admin -> Appearance -> Themes:
       Magically a new child theme icon will appear!
       Activate new child theme.
       Test new theme to ensure it works.

Troubleshooting:
    1. Enable php debug.
    2. Examine and fix errors.

Next Steps:
    1. Add your own functions.
    2. Add more CSS or use your own stylessheet.
        Don't forget to edit functions.php file, if using a different one.