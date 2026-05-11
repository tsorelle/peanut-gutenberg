[Return to docs home page](../index.md)
# Where's the Code?

Custom Code for the FMA site is found in the following locations.
1. The knockout_view package<br>
  Location: web.root/packages/knockout_view
   - controller.php - basic ConcreteCMS startup and management code for the package
   - knockout_view/blocks: Code for custom block templates. see section below.
   -  knockout_view/pnut: The PEANUT framework and code
     - pnut/core: essential core code for PEANUT.  
     - pnut/components -  KnockoutJS components for PEANUT. HTML for these components is in the "templates" directory.
     - pnut/packages - these are code modules mostly containing Knockout views/viewmodels,
       PHP source and configuration items.
     - pnut/extensions - PEANUT UI extension TypeScript classes.
     - pnut/js - Typescript utility classes
     - pnut/js/lib: third-party javascript libraries
       - headjs: used by the PEANUT loader to dynamically include Javascript
       - jquery: contains Jquery ajax and dependencies. Used only if jQuery is not provided by the CMS.
   - knockout_view\src: PHP source files used by peanut core.  AKA "TOPS PHP Library"
2. The peanut_utilities package<br>
  Location: web.root/packages/peanut_utilities 
   - controller.php - ConcreteCMS startup code
   - blocks/pnut_attribute_field  <br>
   The sole purpose of this ConcreteCMS package is to provide the "Peanut Attribute Field" 
   block. This may be refactored later to be included in the knockout_view package.
3. The application directory
   - application/bootstrap
     - app.php: This is a standard part on ConcreteCMS, but inclues our code defining routing.
     - peanut-app.php: this is just a backup copy of app.php with our customizations.
   - application/src: Application specific PHP code.
   - application/peanut: Application speific PEANUT code including ViewModels/Views, KnockoutJS components and PHP source code.
4. Peanut startup code in the theme directory
    - Any theme used with PEANUT must include a small section of code 
   in the elements/footer_bottom.php file.  This is the single entry point
   for the PEANUT system which initiates the process of loading the viewmodels.  See: web.root/application/themes/fma/elements/footer_bottom.php
5. Custom block templates<br>
This code changes be behavior of ConcreteCMS blocks such as PageList and AutoNav.
See: [Templates and Styles](templates-and-styles.md)

   - Fma Top Navigation
       - Used only for the top navigation bar.
       - Source: web.root/application/blocks/top_navigation_bar/templates/fma_top_navigation.php
   - Fma news page list
       - Location: web.root/application/blocks/page_list/templates/fma_news_page_list
   - Fma Featured Event Page List
       - Location: web.root/application/blocks/page_list/templates/fma_featured_events_page_list
   -  Fma collapsible page list
     - Used for committee pages list
       - Location: web.root/application/blocks/page_list/templates/fma_collapsible_page_list
     - Fma events page list
       - todo: not working now. debug
       - Location: web.root/application/blocks/page_list/templates/fma_events_page_list
     - breadcrumbs
       - For breadcrumb menus at top of page
       - Example: /community/committees/care-and-counsel
       - Location: web.root/application/themes/fma/blocks/autonav/templates/breadcrumb.php
       - Note: same as crumbs.php, renamed for backward compatibility with original theme
     - Peanut Authenticated Content
       - hides content from anonymous users
       - Location: web.root/packages/knockout_view/blocks/content/templates/peanut_authenticated_content/view.php
       - Example: footer content column 2 second content block
   - Peanut Html Placeholder
       - Used with HTML blocks that contain non-visual elements to
       make it visible in edit mode. Example in /tasks/testing/service-test
       - Location: web.root/packages/knockout_view/blocks/html/templates/peanut_html_placeholder
     - Theme templates
       - Several templates were provided by concreteCMS in the Atomik theme from which the FMA theme derives.
       - Location: web.root/application/themes/fma/blocks
       - Colapsible Knockout View
       - Used to surround a Knockout View Block with a colapsible section.
       See committee pages for examples.
       - Location: web.root/packages/knockout_view/blocks/knockout_view/templates/collapsible-knockout-view
     - Obsolete templates:
       - FMA Header Navigation - replaced by FMA Top Navagation
       - FMA Landing page menu - Apply the custom class .landing-page-menu instead.
## Note
For information on dynamic object creation ee also [Object Container](../peanut/object-container.md)

