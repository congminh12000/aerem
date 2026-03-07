=== WS Form PRO ===
Contributors: westguard
Requires at least: 5.2
Tested up to: 6.3
Requires PHP: 5.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WS Form PRO allows you to build faster, effective, user friendly WordPress forms. Build forms in a single click or use the unique drag and drop editor to create forms in seconds.

== Description ==

= Build Better WordPress Forms =

WS Form lets you create any form for your website in seconds using our unique drag and drop editor. Build everything from simple contact us forms to complex multi-page application forms.

== Installation ==

For help installing WS Form, please see our [Installation](https://wsform.com/knowledgebase/installation/?utm_source=wp_plugins&utm_medium=readme) knowledge base article.

== Changelog ==

= 1.9.170 - 10/25/2023 =
* Added: New #tracking_duration variable for returning the time it took to submit the form in seconds

= 1.9.169 - 10/19/2023 =
* Added: New #field_count_char(id, "regex_filter") variable for returning the number of characters in a string
* Added: New #field_count_word(id, "regex_filter") variable for returning the number of words in a string
* Added: New "All rows selected" and "Not all rows selected" conditional logic IF conditions for select fields
* Added: New "All rows checked" and "Not all rows checked" conditional logic IF conditions for checkbox fields

= 1.9.168 - 10/11/2023 =
* Bug Fix: Row specific conditional logic

= 1.9.167 - 10/10/2023 =
* Added: Improved conditional logic debug logging
* Added: Improved syntax error output when evaluating variables
* Added: Improved handling or conditional logic select, checkbox and radio row handling
* Changed: DropzoneJS default placeholder text

= 1.9.166 - 10/04/2023 =
* Added: Improved handling of sidebar default values in admin
* Added: Changed captcha resetting to only occur on form submit

= 1.9.165 - 09/30/2023 =
* Bug Fix: Class meta key used on file and password fields

= 1.9.164 - 09/29/2023 =
* Bug Fix: Fields with suffix had formatting issue with new help text system

= 1.9.163 - 09/29/2023 =
* Added: Help text positioning (Top or bottom) that can be set at a form or field level

= 1.9.162 - 09/23/2023 =
* Added: Google routing field waypoint support
* Added: Cascade source now supports hidden fields
* Added: WordPress plugin standards compliance improvements
* Bug Fix: #file_index on custom file names

= 1.9.161 - 09/19/2023 =
* Added: New priority for Run WordPress Hook action: Before submission created
* Bug Fix: Number field step attribute when hidden

= 1.9.160 - 09/17/2023 =
* Added: Clear and reset conditional logic options for cart price fields
* Added: Improved handling of embedded variables in attributes on server-side

= 1.9.159 - 09/16/2023 =
* Added: File upload image restrictions for image max width and max height
* Added: File upload image restrictions now shown against each file when using DropzoneJS
* Added: Debug console populate function now obeys input masks
* Added: wsf_loaded hook
* Added: Form import now strips BOM if found
* Bug Fix: Debug console populate date fields with min / max ranges
* Bug Fix: Input mask enqueuing for currency if only cart detail / cart total fields present

= 1.9.158 - 09/13/2023 =
* Added: File upload image restrictions for image width, height and aspect ratio
* Added: Form limits without message types no support server-side WS Form variables

= 1.9.157 - 09/07/2023 =
* Added: Form limit messages now support server-side WS Form variables
* Bug Fix: ACF radio and button group meta value

= 1.9.156 - 09/06/2023 =
* Bug Fix: ACF post object mapping meta value

= 1.9.155 - 09/05/2023 =
* Added: ACF field validation override if validation filters return invalid response.
* Bug Fix: Phone field inside label positioning if ITI configured to use country code selector

= 1.9.154 - 08/23/2023 =
* Added: gtag automatically exposed to dataLayer for Google Tag Manager implementations
* Added: Legal field calculation improvements
* Added: Privacy improvements with author variables
* Bug Fix: Server-side Belgian French date translations
* Bug Fix: Conversational date picker

= 1.9.153 - 08/21/2023 =
* Added: Google Tag Manager (Data Layer) conversion type that only runs dataLayer.push(params)

= 1.9.152 - 08/18/2023 =
* Added: Custom attributes for hidden fields
* Added: Accessibility improvements in the layout editor (Improved color contrasts and ARIA attribute review)
* Added: Reduced page size when exporting submissions to reduce server memory usage
* Bug Fix: Language string issue when clicking to add fields

= 1.9.151 - 08/14/2023 =
* Added: Support for GA4 Enhanced Event Measurement form_start & form_submit events

= 1.9.150 - 08/09/2023 =
* Added: Added InstaWP to LITE readme.txt
* Bug Fix: Select2 default option preload in admin sidebar

= 1.9.149 - 08/08/2023 =
* Added: WordPress 6.3 compatibility testing
* Added: Additional functionality added to action form engine for InstaWP add-on

= 1.9.148 - 08/03/2023 =
* Bug Fix: Conditional logic on fields within repeatable sections with a source outside of a repeatable section

= 1.9.147 - 08/03/2023 =
* Added: Conditional logic actions for loader
* Added: Form instance style targeting for loader

= 1.9.146 - 08/02/2023 =
* Added: Loader / spinner system for form submit, save, action and render events
* Added: Debug console color improvements
* Bug Fix: Webhook custom mapping on legacy data
* Bug Fix: Signature resize event if hidden
* Bug Fix: Required indicator on legal field

= 1.9.145 - 07/28/2023 =
* Bug Fix: ACF custom field column mapping in posts data source

= 1.9.144 - 07/27/2023 =
* Bug Fix: Selector escaping in admin

= 1.9.143 - 07/26/2023 =
* Added: Session ID now automatically clears if the associated submission record has been trashed
* Added: Improved text area formatting in submissions sidebar
* Added: Improved JS selector escaping throughout
* Added: Post data source now supports reading field data contained within a group
* Bug Fix: If a tab is clicked using conditional logic it now shows the tab to ensure the fields are submitted

= 1.9.142 - 07/22/2023 =
* Added: Cascading select fields configured to use AJAX can now be pre-populated
* Added: Improved German translations in debug console
* Added: ID deduplication during data grid CSV import
* Added: Improved scroll detection for legal field

= 1.9.141 - 07/19/2023 =
* Added: Geolocation Lookup by IP in Form Settings that allows you to populate fields with gelocation data such as city and region
* Added: Two additional IP lookup endpoints; ipinfo.io and ipapi.co
* Added: Setting to include admin traffic in statistics data added to Basic tab of Global Settings

= 1.9.140 - 07/11/2023 =
* Bug Fix: Escaping issue in admin sidebar for disabling unique values in mapping repeaters

= 1.9.139 - 07/08/2023 =
* Added: Ability to set license keys using a named constant in wp-config.php https://wsform.com/knowledgebase/setting-license-keys-with-php-constants/

= 1.9.138 - 07/06/2023 =
* Added: AI generated form support for OpenAI add-on
* Added: Updated DropzoneJS to version 5.9.3
* Bug Fix: data-hidden-bypass fix

= 1.9.137 - 07/04/2023 =
* Bug Fix: Running calculations when repeatable section is moved up or down

= 1.9.136 - 07/03/2023 =
* Added: JetEngine checkbox data format updates

= 1.9.135 - 07/03/2023 =
* Added: Ability to use the form locate feature when using Bricks site builder
* Bug Fix: JetEngine user field options

= 1.9.134 - 06/29/2023 =
* Added: Support for non-English dates in submission date range search
* Added: Add Media button toggle for the visual editor text area type, only visible to logged in users with the upload_files capability
* Bug Fix: Initial tab validation if conditions existed

= 1.9.133 - 06/26/2023 =
* Added: Removed fonts.googleapis.com dependency in admin CSS
* Bug Fix: CSS for template SVG's enqueuing incorrectly

= 1.9.132 - 06/23/2023 =
* Added: Multiple setting for Radio fields in IF conditions
* Added: Paste as Text setting for text area visual editor to determine default state of Paste as Text button
* Added: Hooks to customize text area visual editor buttons https://wsform.com/knowledgebase/customize-the-visual-editor-toolbar/
* Bug Fix: Removed form-control class around captcha fields for Bootstrap 5

= 1.9.131 - 06/20/2023 =
* Added: Improved handling of Send Email action CC / BCC filters

= 1.9.130 - 06/13/2023 =
* Bug Fix: Removed use of :is pseudo element in hidden field handler

= 1.9.129 - 06/13/2023 =
* Bug Fix: Fixed regex example for Phone - General
* Bug Fix: Fixed required field management for conversational forms

= 1.9.128 - 06/10/2023 =
* Added: Additional Send Email action hooks for BCC, CC and Reply To settings
* Bug Fix: Conditional logic initial firing on duplicate elements

= 1.9.127 - 06/06/2023 =
* Bug Fix: Fixed JavaScript issue with form selector on submissions page

= 1.9.126 - 06/05/2023 =
* Added: Tab active / not active conditional logic
* Bug Fix: Output buffer clear fix

= 1.9.125 - 06/01/2023 =
* Added: Improved population of ACF number fields
* Bug Fix: Ensured variable escaping is not considered translatable throughout

= 1.9.124 - 05/29/2023 =
* Added: #number_format(number, decimals, decimal_separator, thousands_separator) variable
* Added: #field_date_Format(field_id, date_format) variable

= 1.9.123 - 05/28/2023 =
* Added: Support for pushing select, checkbox and radio values to Pods text fields
* Bug Fix: File upload handler setting featured image on posts if using default file upload type

= 1.9.122 - 05/26/2023 =
* Added: Required setting on price fields
* Added: Price fields converted to float prior to being pushed to ACF number fields

= 1.9.121 - 05/25/2023 =
* Added: Required indicator added to checkbox label if 'Minimum Checked' setting is set
* Added: Added additional field types that can be mapped to Google Address fields
* Added: Override CSS to overcome styling issues found with poorly targeted CSS from third party plugins
* Changed: Removed single pixel GIF that was used to overcome an old formatting issue with Mac mail

= 1.9.120 - 05/24/2023 =
* Added: Improved saved data format for ACF mappings

= 1.9.119 - 05/23/2023 =
* Bug Fix: Review admin message

= 1.9.118 - 05/22/2023 =
* Added: Support for additional classes on block
* Added: Ability to specify custom title, caption, description and alt tag on media library uploads
* Added: Translation improvements
* Changed: Block preview now changed to GIF as opposed to SVG to avoid CSS issues with preview being in iframe
* Changed: Captcha fields can no longer be bypassed if they are hidden to improve security
* Bug Fix: Progress on repeaters
* Bug Fix: Mobile breakpoint font size in email template
* Bug Fix: Down arrow on select fields no longer obscured by long option text

= 1.9.117 - 05/18/2023 =
* Added: Accessibility improvements for honeypot field
* Added: Accessibility improvements for help text
* Added: Repeatable section row delete confirmation setting
* Added: Translated month and day names during date validation to support other languages
* Bug Fix: Conditional logic initial firing on repeatable section row adding

= 1.9.116 - 05/13/2023 =
* Added: Multiple row selection in conditional logic for row visibility, required, disabled, class
* Bug Fix: Image / file custom field integration issue with existing attachments

= 1.9.115 - 05/13/2023 =
* Bug Fix: #calc initial value was causing issues with HTML fields

= 1.9.114 - 05/11/2023 =
* Added: Multiple row selection in conditional logic for row select/check
* Added: Support for Google Routing in repeatable sections
* Bug Fix: #field delimiter parameter was trimmed

= 1.9.113 - 05/09/2023 =
* Changed: Quantity min / max bound checking now only occurs on field change

= 1.9.112 - 05/08/2023 =
* Bug Fix: WS Form (Private) file handler in repeatable sections
* Bug Fix: PDF image width in custom templates

= 1.9.111 - 05/05/2023 =
* Added: Removed invalid event handler inputmask fields to fix blur issue if setCustomValidity applied to it

= 1.9.110 - 05/04/2023 =
* Added: CSS max height in email template for signature field to avoid large images showing
* Changed: Webhook no longer halts action processing if node not found in response data
* Bug Fix: Custom validity recall issue if set in conditional logic and field then hidden

= 1.9.109 - 05/03/2023 =
* Bug Fix: #calc price value

= 1.9.108 - 05/02/2023 =
* Added: Input mask validation

= 1.9.107 - 05/01/2023 =
* Added: zxcvbn no longer enqueued if not required by password field
* Added: Improved handling of UTC date for 'Current Day' deduplication setting
* Bug Fix: Uncompiled non-inline API CSS path

= 1.9.106 - 04/25/2023 =
* Added: Flush on output if zip or file downloaded to overcome issues with rogue character in output buffer
* Added: Admin sidebar changes to prevent issues with poorly enqueued jQuery from third party plugins
* Bug Fix: Admin sidebar would not open if form text editor or HTML field contained wsf-field-wrapper class
* Bug Fix: #calc issue with calc object registering incorrect source field ID

= 1.9.105 - 04/24/2023 =
* Added: Support for JetEngine checkbox 'Save as Array' setting
* Bug Fix: #calc support in #field_date_offset

= 1.9.104 - 04/22/2023 =
* Added: Elementor widget updates

= 1.9.103 - 04/21/2023 =
* Added: Performance improvements for #calc / #text

= 1.9.102 - 04/21/2023 =
* Added: Support for JetEngine 'Is Timestamp' setting on date fields
* Bug Fix: Email validation

= 1.9.101 - 04/18/2023 =
* Added: Improved data source error handling and scheduling
* Added: Overrides for latest WordPress block editor CSS

= 1.9.100 - 04/17/2023 =
* Added: 'Importing ...' notice added to import progress bar
* Bug Fix: Calculation triggering on hidden fields

= 1.9.99 - 04/17/2023 =
* Added: Edit in Preview now supports file upload edits
* Added: Improved performance on file upload previews

= 1.9.98 - 04/16/2023 =
* Bug Fix: Dynamic #text label and help text on fields that did not allow dynamic #text on value
* Bug Fix: Statistics divide by zero issue

= 1.9.97 - 04/14/2023 =
* Added: Google Routing field. Provides distance and duration calculations between a start and end location. Learn more: https://wsform.com/knowledgebase/google-route/
* Added: Google Maps template category
* Added: Google Routing templates
* Added: Mapping category in toolbox fields sidebar

= 1.9.96 - 04/12/2023 =
* Added: Support for plain permalink URL structure
* Added: Improved #email_logo handling to avoid bugs caused by third party software
* Bug Fix: #text with color fields now sets color panel preview

= 1.9.95 - 04/10/2023 =
* Bug Fix: Reports scheduling when upgrading from LITE to PRO

= 1.9.94 - 04/10/2023 =
* Added: Type selector for WebHook field and custom mapping

= 1.9.93 - 04/07/2023 =
* Bug Fix: Checkbox and radio deduplication in repeatable sections

= 1.9.92 - 04/06/2023 =
* Added: Additional checks to ensure data passed to remote requests is valid
* Added: Support for roles that do not have capabilities assigned to them
* Bug Fix: Conditions no longer assesssed if source event is outside of repeatable section and IF condition contains fields in a repeatable section

= 1.9.91 - 04/05/2023 =
* Bug Fix: Dashboard date range fix

= 1.9.90 - 04/04/2023 =
* Added: WS Form --> Settings --> Reports setting allows for daily, weekly or monthly form statistics report sent via email
* Added: 'Show File Name and Size' setting under WS Form --> Settings --> Variables which removes file name and size under files and signatures in email and PDF templates
* Added: Signatures now recalled in saved forms
* Added: Date/time picker now has setting for moving the element within the form to avoid issues with parent elements having position: relative
* Changed: Signature cropping now moved server-side (Requires Imagick)
* Changed: WS Form --> Settings --> Email is now Variables
* Bug Fix: Patch implemented to overcome Safari issue with times

= 1.9.89 - 04/03/2023 =
* Added: Moved date/time picker element within form instead of within body to allow styling by form
* Added: Added ID to each date/time picker element
* Added: Custom class setting for date/time picker element

= 1.9.88 - 03/31/2023 =
* Added: Performance improvements to select, checkbox and radio fields (empty row attributes removed)
* Added: Performance improvements to repeatable section initialization
* Added: Performance improvements to form validation
* Added: Functionality for new PDF add-on variables #pdf_url and #pdf_link
* Bug Fix: WS Form framework was not being used in block editor for layout CSS if using Bootstrap
* Bug Fix: Section ID repair in imported meta data

= 1.9.87 - 03/29/2023 =
* Added: WordPress 6.2 compatibility testing
* Added: Requirements added to main PHP file
* Bug Fix: Capitalize transform feature was not working for some european characters

= 1.9.86 - 03/29/2023 =
* Changed: Simplified Human Presence functionality to rely on its own confidence level

= 1.9.85 - 03/28/2023 =
* Bug Fix: Layout editor no longer saves if sidebar is locked (e.g. requesting data from an API)

= 1.9.84 - 03/26/2023 =
* Added: Update ready for Breakdance v1.3
* Added: Check on SVG creation to ensure form object is valid

= 1.9.83 - 03/23/2023 =
* Bug Fix: JetEngine media and gallery field population

= 1.9.82 - 03/23/2023 =
* Added: Improved button locking functionality
* Added: Improved form checksum validation on import
* Bug Fix: Media library meta data when custom file name specified
* Bug Fix: Webhook encoding of values containing arrays

= 1.9.81 - 03/18/2023 =
* Added: Patch to ensure adding parameters to API URL using add_query_arg does not convert periods to underscores

= 1.9.80 - 03/18/2023 =
* Bug Fix: API call fix for AWeber

= 1.9.79 - 03/17/2023 =
* Added: Spam level indicators added to LITE edition
* Added: Additional admin CSS fixes for third party plugin that incorrectly enqueued Bootstrap on every page
* Bug Fix: Submission records were still created if Human Presence detected spam

= 1.9.78 - 03/15/2023 =
* Added: PDF previews on DropzoneJS file uploads
* Added: Ability to preview / download DropzoneJS file uploads in submissions when form saved
* Added: Admin CSS fixes for third party plugin that incorrectly enqueued Bootstrap on every page

= 1.9.77 - 03/10/2023 =
* Bug Fix: db_get_submit_meta duplicating values if run via WooCommerce extension

= 1.9.76 - 03/09/2023 =
* Added: Webhook API response mapping now supports array in JSON response
* Added: Webhook now has improved handling of query variables in GET requests

= 1.9.75 - 03/08/2023 =
* Added: Improved Google Events functionality https://wsform.com/knowledgebase/google-events/
* Added: Improved handling of repeatable fallback values on submissions page 

= 1.9.74 - 03/06/2023 =
* Bug Fix: Label positioning adjustment on phone fields

= 1.9.73 - 03/02/2023 =
* Added: Support for glossaries in JetEngine Field Options data source
* Added: Set minimum/maximum date and time conditional logic for date/time field type
* Added: Enabled times feature on date/time field type
* Added: Separated min/max time setting on date/time field configured as type 'Date/time' or 'Time'
* Added: Field match conditional logic support for values inside a repeater
* Bug Fix: CSS fix for Phone field configured to use inside label with international telephone input enabled

= 1.9.72 - 02/28/2023 =
* Added: Context to JetEngine get field settings method to support user fields

= 1.9.71 - 02/27/2023 =
* Added: Set title of email template to email subject
* Added: Default file type set to DropzoneJS on file upload fields
* Bug Fix: Custom file name on media library files

= 1.9.70 - 02/22/2023 =
* Added: Patch to overcome older browsers not supporting date formats in Y-m-d H:i:s format

= 1.9.69 - 02/21/2023 =
* Added: Form validation when required status of field changes
* Bug Fix: Redirect action URL parsing

= 1.9.68 - 02/17/2023 =
* Added: Additional functionality for PDF add-on

= 1.9.67 - 02/15/2023 =
* Bug Fix: ITI (International Telephone Input) for multiple form instances on a single page

= 1.9.66 - 02/14/2023 =
* Added: Ability to assign multiple values to a single key in Webhook (converted to array with dedupe setting)
* Added: wsf_dropzonejs_upload_path filter for DropzoneJS upload path
* Added: Dependencies for DropzoneJS enqueue

= 1.9.65 - 02/07/2023 =
* Added: Improved rendering of conditional logic in admin sidebar
* Added: Support for population of hidden fields for Google Address field
* Added: Edit in Preview link on submissions (Must be enabled in settings)

= 1.9.64 - 02/06/2023 =
* Added: Meta Box custom table support
* Bug Fix: Changing a hidden required field to no required was reverted to required with made visible
* Bug Fix: Dashboard component registration if user capability not set

= 1.9.63 - 02/05/2023 =
* Added: Event resetting on form reload
* Bug Fix: #calc / #text now processes original source instead of parsed source 
* Bug Fix: Conditional logic on conversational forms

= 1.9.62 - 02/03/2023 =
* Added: Patch to overcome known bug with :valid / :invalid selectors in certain browsers

= 1.9.61 - 02/03/2023 =
* Added: Support for population of select fields configured with Select2 + AJAX
* Added: Custom attributes for divider field type
* Bug Fix: Field class setting for divider field type

= 1.9.60 - 02/02/2023 =
* Bug Fix: Setting values in textarea fields using TinyMCE

= 1.9.59 - 02/02/2023 =
* Bug Fix: WooCommerce extension file upload field processing
* Bug Fix: Sidebar select2 caching issue

= 1.9.58 - 02/01/2023 =
* Bug Fix: Setting price values with conditional logic or Webhook return

= 1.9.57 - 01/30/2023 =
* Bug Fix: reCAPTCHA V3 timeout issue

= 1.9.56 - 01/29/2023 =
* Bug Fix: Setting values on hidden fields with conditional logic or Webhook return

= 1.9.55 - 01/28/2023 =
* Added: Saved File Name setting for file uploads to allow for custom file names

= 1.9.54 - 01/27/2023 =
* Bug Fix: Setting label on button configured as submit using conditional logic

= 1.9.53 - 01/26/2023 =
* Added: Forced 200 HTTP response if API requests successful (third parties were changing this in hook)
* Added: Firefox JS number rounding bug patch (their number field is limited to 14 decimal places if certain languages are set)
* Added: Change to node value set function for Webhook function to avoid error if node key used twice in mappings
* Bug Fix: Sidebar select2 initialization issue

= 1.9.52 - 01/25/2023 =
* Added: Improved CSS escaping for skin output
* Bug Fix: Conversational progress bar

= 1.9.51 - 01/25/2023 =
* Bug Fix: Tab validation showing validation errors was not working with Bootstrap framework
* Bug Fix: get_user_id function

= 1.9.50 - 01/24/2023 =
* Changed: Push to Custom Endpoint renamed to Webhook
* Added: Return field mapping functionality on Webhook action allows return from API requests to populate form fields
* Added: Support for dot notation in Webhook field, custom and return mapping (e.g. result[0].name.first)
* Added: SSL Verify, Timeout and Cookie Passthrough options to Webhook
* Added: Webhook Return Mapping demo template
* Added: Change event to Hidden field conditional logic
* Bug Fix: Select2 AJAX results conditional logic no longer fires on form render

= 1.9.49 - 01/23/2023 =
* Bug Fix: Next tab button disabling using prop method

= 1.9.48 - 01/22/2023 =
* Added: JetEngine integration support for Post Management (1.5.0) add-on
* Added: JetEngine integration support for User Management (1.5.0) add-on
* Added: JetEngine field option data source
* Added: Select2 custom message settings
* Added: Select2 AJAX results conditional logic
* Added: Improved custom action detection

= 1.9.47 - 01/19/2023 =
* Bug Fix: wpautop on text/plain content type emails

= 1.9.46 - 01/19/2023 =
* Added: Additional parameters added to cookies when set 'SameSite=Strict; Secure'
* Bug Fix: ACF button group saving

= 1.9.45 - 01/18/2023 =
* Bug Fix: Button disabling with WooCommerce extension

= 1.9.44 - 01/17/2023 =
* Added: ip-api as a geolocation service
* Added: geoPlugin / ip-api API key settings for commercial versions of geolocation services
* Added: Improved geolocation request headers to avoid invalid usage messages
* Added: Google Address component mapping options added for Street full - Short/Long (Reverse order)
* Bug Fix: Submission not assessing available fields correctly when layout only fields or user role restrictions imposed

= 1.9.43 - 01/16/2023 =
* Added: Updated Elementor widget registration code for version 3.5+
* Added: Updated PHP sort functions

= 1.9.42 - 01/15/2023 =
* Added: Meta value sorting option for posts data source

= 1.9.41 - 01/13/2023 =
* Added: New support functions for OpenAI add-on

= 1.9.40 - 01/09/2023 =
* Bug Fix: Deduplication using 'Current Day' method

= 1.9.39 - 01/09/2023 =
* Added: Do not apply wpautop functionality to client side #field use
* Added: Ability to use HTML in #field delimiter

= 1.9.38 - 01/08/2023 =
* Changed: Removed fitBounds event when Google Map search place is clicked on to stop zoom irregularities

= 1.9.37 - 12/31/2022 =
* Bug Fix: International telephone input threw error if form submitted too quickly (e.g. with conditional logic)

= 1.9.36 - 12/28/2022 =
* Added: Improved mark-up in email templates

= 1.9.35 - 12/25/2022 =
* Bug Fix: wpautop on submissions page

= 1.9.34 - 12/21/2022 =
* Added: Server side validation now applies to all mappable fields
* Changed: wpautop is now applied to Text Area fields configured as 'Default' when output in actions (e.g. Emails). #wpautop is no longer required to format this content. A 'Do Not Apply wpautop' setting is also available under 'Advanced' for Text Area fields if you want to revert to an unformatted output.

= 1.9.33 - 12/16/2022 =
* Added: Performance improvement on form submit

= 1.9.32 - 12/13/2022 =
* Added: Sidebar lock when retrieving integration data
* Bug Fix: CodeMirror mode for JavaScript editors

= 1.9.31 - 12/09/2022 =
* Added: Global setting for reCAPTCHA, hCaptcha and Turnstile keys

= 1.9.30 - 12/05/2022 =
* Bug Fix: Captcha silent validation

= 1.9.29 - 12/02/2022 =
* Added: Further improvements to license management

= 1.9.28 - 12/01/2022 =
* Added: Improved licensing management to avoid license deactivation on DB locks

= 1.9.27 - 11/30/2022 =
* Bug Fix: Tags label when creating form from action template

= 1.9.26 - 11/29/2022 =
* Added: Improved honeypot field for accessibility
* Bug Fix: Logging error in LITE edition for captcha fields

= 1.9.25 - 11/25/2022 =
* Added: Performance improvement on client side. Custom field configuration no longer loading in data sources.
* Added: Support for serialized data in submissions page when added to hidden field meta data

= 1.9.24 - 11/18/2022 =
* Added: Support for WooCommerce booking plugin

= 1.9.23 - 11/16/2022 =
* Added: Validate number setting under international telephone input setting on phone field
* Added: #session_storage_get(key) variable
* Added: #local_storage_get(key) variable
* Added: Placeholder setting to date / time field

= 1.9.22 - 11/15/2022 =
* Bug Fix: Form validation on Select2 + AJAX pre-populated fields

= 1.9.21 - 11/15/2022 =
* Added: Support for Meta Box relationship fields
* Bug Fix: Field / tab focus on form submit

= 1.9.20 - 11/12/2022 =
* Added: Ability to use min/max with repeatable #field(123) input
* Changed: Progress bar CSS to ensure right border radius appears at 100%

= 1.9.19 - 11/10/2022 =
* Change: Duration cookie removed in LITE edition
* Bug Fix: Next tab validation to show invalid feedback if tabs were hidden

= 1.9.18 - 11/09/2022 =
* Added: Support for dynamic enqueuing on payment forms that don't include e-commerce fields

= 1.9.17 - 11/09/2022 =
* Bug Fix: wsf_field_row_add function fixed

= 1.9.16 - 11/07/2022 =
* Added: Breakdance website builder element
* Added: Ability to use variables in the custom endpoint action URL
* Bug Fix: Field step bypass on hidden fields 

= 1.9.15 - 11/02/2022 =
* Bug Fix: Data format fix for Meta Box select fields without multiple setting enabled

= 1.9.14 - 10/30/2022 =
* Added: Support for WooCommerce geolocalize users with cache
* Added: wsf_form_get_count_submit function for returning total public form submissions by form ID

= 1.9.13 - 10/29/2022 =
* Added: Support for quantity values being populated using different decimal separators
* Bug Fix: Quantity field with decimals when using character other than period for decimal separator

= 1.9.12 - 10/27/2022 =
* Changed: Database primary key and relational field types changed to match WordPress
* Bug Fix: Show dynamic enqueuing setting for all frameworks

= 1.9.11 - 10/26/2022 =
* Added: Honeypot SEO improvement
* Bug Fix: Submissions read/unread display
* Bug Fix: wpautop JavaScript function

= 1.9.10 - 10/22/2022 =
* Bug Fix: Conditional logic for setting repeatable section rows

= 1.9.9 - 10/22/2022 =
* Added: wsf_form_get_fields_by_label function
* Bug Fix: JavaScript action registration

= 1.9.8 - 10/21/2022 =
* Added: unfiltered_html capability extended to data grids

= 1.9.7 - 10/20/2022 =
* Added: Compliance with the unfiltered_html capability which prevents saving of unfiltered HTML on all object data (https://wsform.com/knowledgebase/preventing-users-from-saving-markup-in-the-layout-editor/)
* Bug Fix: Default button colors
* Bug Fix: Cascade dynamic enqueuing

= 1.9.6 - 10/19/2022 =
* Added: Reviewed wpdb functionality throughout
* Added: Improved DropzoneJS error handling
* Added: Form and submit objects to email filters
* Bug Fix: Submission export animated gif

= 1.9.5 - 10/18/2022 =
* Added: Support for performance plugins with dynamic enqueue
* Changed: Removed WP_List_Table class to rely on class included with WordPress core
* Bug Fix: Password confirmation conditional logic if password strength invalid feedback enabled

= 1.9.4 - 10/17/2022 =
* Bug Fix: GeoIP lookup endpoint
* Bug Fix: Tab indexing on refresh

= 1.9.3 - 10/17/2022 =
* Bug Fix: Date variables without date fields

= 1.9.2 - 10/17/2022 =
* Added: #field with delimiter support for price select, price checkbox and price radio fields
* Added: Quality and price total auto map on clone
* Bug Fix: Tab indexing on clone

= 1.9.1 - 10/16/2022 =
* Added: Reviewed santitization, escaping and validation throughout
* Added: Improved code to make security reviews easier in future

= 1.9.0 - 10/14/2022 =
* Added: Dynamic enqueuing setting (Global settings)
* Added: Public JS optimized throughout to dramatically reduce file download size
* Added: Minimum password strength setting on password field
* Added: Suggest password setting on password field
* Added: Generate password option in conditional logic for password fields
* Bug Fix: Population of price checkbox fields
