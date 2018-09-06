# 404 Solution #

Automatically redirect 404s when the slug matches (for permalink changes), when a very similar name match is found, or always to a default page.

## Description ##

404 Solution logs 404s and allows them to be redirected to pages that exist. Redirects can also be created based on the best possible match for the URL the visitor was most likely trying to reach.

### Features: ###

* Redirect 404 URLs to existing pages or ignore them.
* Automatically create redirects based on the URL the visitor was most likely trying to visit.
* Get a list of 404 URLs as they happen.
* View logs of hits to 404 pages and redirects including referrer data.
* WooCommerce compatible - pages, posts, products, and custom post types are supported.
* Automatically redirect to the correct page after a permalink structure change.
* Show possible page matches on a 404 page.
* Basic plugin usage statistics.
* Automatically remove redirects when the URL matches a new page or post permalink.
* Automatically remove manual and automatic redirects once they are no longer being used.
* Redirect based on a RegEx (regular expression) pattern.

Convert your 404 traffic by providing your visitors with a better browsing experience and eliminate 404 URLs on your site.

## Etc ##
* Untested: Test whether changing the permalink structure twice (3 different permalinks structures total) causes us to
    forward twice unnecessarily. Test by doing the following: Choose a permalink structre, create a page, change the 
    permalink structure, go to the old URL of the page to create an automatic redirect, change the permalink structure
    to a third type, go to the old URL of the page. Are we forwarded to the second (non-existent) permalink structre and 
    not the current valid one?  Workaround: Delete all redirects and start over.
* To be added: Multilingual support using icl_object_id.

## Installation ##

1. Unzip the files and upload the contents to `/wp-content/plugins/`.
2. Activate the plugin.
3. Use the `Settings -> 404 Solution` options page to set the options.

## Frequently Asked Questions ##

### How long does it take for 404 URLs to start showing up? ###

As long as the "Capture incoming 404 URLs" option is enabled in the options section, the 404 URLs will show up in the captured list as soon as a visitor hits a 404 page.

### Will there be a slow down on my site when running the plugin? ###

No, there should be no noticeable slow down when running the plugin on your site.

### Will this plugin redirect my pages if I change my permalinks structure? ###

Yes! 404 Solution records the page/post ID number and looks up the most current permalink before redirecting the user.

### Can I redirect all 404's to a particular page? ###

Yes. It's as easy as turning on this feature in the options. 

### How do I delete log files? How do I purge log lines? ###

Deleting old log lines to limit disk space usage is done automatically. You can set the maximum size to as low as 1MB under Options -> General Settings -> Maximum log disk usage.

## Screenshots ##

1. Admin Options Screen
![1. Admin Options Screen](http://plugins.svn.wordpress.org/404-solution/trunk/assets/screenshot-1.jpg)

2. Logs
![2. Logs](http://plugins.svn.wordpress.org/404-solution/trunk/assets/screenshot-2.jpg)

3. Create New Redirect
![3. Create New Redirect](http://plugins.svn.wordpress.org/404-solution/trunk/assets/screenshot-3.jpg)

## Changelog ##

## Version 2.10.0 (September 6, 2018) ##
* FIX: Maintenance to delete duplicates now deletes the oldest duplicate rows instead of the most recent ones (thanks Marc Siepman).
* FIX: A debug line that is now GDPR compliant (according to the options).
* Improvement: Minor changes to avoid rare error messages for some users.

## Version 2.9.5 (July 4, 2018) ##
* FIX: Include a list of all of the post types in the database on the options page (for Mauricio).

## Version 2.9.4 (July 2, 2018) ##
* FIX: Work with earlier versions of PHP again (bug introduced in 2.9.3).

## Version 2.9.3 (July 1, 2018) ##
* FIX: The "Files and Folders Ignore Strings" setting now works better (for Phil).

## Version 2.9.2 (July 1, 2018) ##
* FIX: Regex redirects can now be emptied from the trash (for VA3DBJ bug #23).

## Version 2.9.1 (May 24, 2018) ##
* FIX: Custom taxonomies: allow entering the taxonomy name instead of the children of taxonomies to use them.

## Version 2.9.0 (May 17, 2018) ##
* Improvement: Support custom taxonomies.
* Improvement: Allow group matching and replacements in regular expression matches.

## Version 2.8.0 (April 26, 2018) ##
* Feature: When a recognized image extension is requested, only images are used as possible matches.

## Version 2.7.0 (April 19, 2018) ##
* FIX: Hash IP addresses before storing them to be General Data Protection Regulation (GDPR) friendly (for Marc).

## Version 2.6.4 (April 14, 2018) ##
* FIX: Try to avoid an activation error on older php versions for HuntersServices.

## Version 2.6.3 (April 13, 2018) ##
* FIX: Correct a minor levenshtein algorithm bug introduced in 2.6.2 when no pages match a URL.

## Version 2.6.2 (April 12, 2018) ##
* FIX: Allow editing a RegEx URL and keeping the RegEx status (thanks joseph_t).
* FIX: Maintain a query string when redirecting in some cases (such as RegEx redirects) (thanks joseph_t).

## Version 2.6.1 (February 24, 2018) ##
* FIX: RegEx redirects support external URLs.
* FIX: The Levenshtein algorithm improvement works with URLs up to 2083 characters in length (up from 300).
* FIX: Try to avoid an issue where strange URLs starting with ///? are returned.

## Version 2.6.0 (February 2, 2018) ##
* Feature: Use RegEx (regular expressions) to match URLs and redirect to specific pages.
* Feature: New option: The Settings menu can be under "Settings" or at the same level as the "Settings" and "Tools" menus.
* Feature: Optionally send an email notification when a certain number of 404s are captured.
* FIX: Delete old redirects based on when they were last used instead of the date they were created.
* Improvement: Allow ordering redirects and captured 404s by the "Last Used" (most recently used date) column on the admin page.
* Improvement: Add the logged in "user" column to the logs table.
* Improvement: Matching categories and tags works a little better than before.
* Improvement: Use a faster, more memory efficient Levenshtein algorithm.

## Version 2.5.4 (December 18, 2017) ##
* Improvement: Improved error message for the customLevenshtein function.
* FIX: Handle a version upgrade without an SQL error when the old logs table doesn't exist 
    (thanks to the user error reporting option).

## Version 2.5.3 (December 6, 2017) ##
* FIX: Work with URLs longer than 255 characters (for lestadt).

## Version 2.5.2 (December 3, 2017) ##
* FIX: Work with PHP version 5.2 again (5.5 required otherwise) (thanks Peter Ford).

## Version 2.5.1 (December 3, 2017) ##
* FIX: Work with PHP version 5.4 again (5.5 required otherwise) (thanks moneyman910!).

## Version 2.5.0 (December 2, 2017) ##
* FIX: Avoid a critical issue that may have caused an infinite loop in rare cases when updating versions.
* Feature: Add an option to email the log file to the developer when there's an error in the log file.
* Feature: Add the [abj404_solution_page_suggestions] shortcode to display page suggestions on custom 404 pages.
* Improvement: Optimize the redirects table after emptying the trash (thanks Christos).
* Improvement: Add a button to the "Page Redirects" to scroll to the "Add a Manual Redirect" section (for wireplay).
* Improvement: Remove the page suggestions on/off option. To turn it off, don't include the shortcode.
* FIX: Ordering redirects and 404s by the 'Hits' column works again (broken in 2.4.0) (thanks Christos).
* FIX: Duplicate redirects are no longer created when a user specified 404 page is used.

## Version 2.4.1 (November 27, 2017) ##
* FIX: Make the 'Empty Trash' button work for lots of data (for Christos).

## Version 2.4.0 (November 26, 2017) ##
* Improvement: Major speed improvement on 'Redirects' and 'Captured' tabs when there are lots of logs.

## Version 2.3.2 (November 25, 2017) ##
* Improvement: Minor efficiency improvements to work better on larger sites.

## Version 2.3.1 (November 24, 2017) ##
* FIX: Try to fix the Captured 404 URLs page when there is a lot in the logs table (for Christos).

## Version 2.3.0 (November 10, 2017) ##
* Improvement: Add an "Organize Later" category for captured 404s (for wireplay).
* Improvement: Add an advanced option to ignore a set of files or folders (for Hans Glyk).

## Version 2.2.2 (November 5, 2017) ##
* FIX: The first usage of the options page didn't work on fresh installations (Lee Hodson).

## Version 2.2.1 (November 4, 2017) ##
* FIX: The options page was unusable on fresh installations (Lee Hodson).

## Version 2.2.0 (October 29, 2017) ##
* FIX: Display child pages under their parent pages on admin screen dropdowns (for wireplay).

## Version 2.1.1 (September 24, 2017) ##
* FIX: Order the list of pages, posts, etc in dropdown boxes again (broken since 2.1.0. thanks to Hans im Glyk for reporting this).

## Version 2.1.0 (September 23, 2017) ##
* Improvement: Don't suggest or forward to product pages that are hidden in WooCommerce, for ajna667.

## Version 2.0.0 (September 20, 2017) ##
* Improvement: Speed up the Captured 404s page for blankpagestl.

## Version 1.9.3 (September 16, 2017) ##
* FIX: Try to fix Rickard's MAX_JOIN_SIZE issue.

## Version 1.9.2 (September 15, 2017) ##
* FIX: Try to fix techjockey's out of memory issue on the options page with an array.

## Version 1.9.1 (September 14, 2017) ##
* FIX: Try to fix techjockey's out of memory issue on the options page.

## Version 1.9.0 (August 12, 2017) ##
* FIX: Allow manual redirects to forward to the home page.
* Improvement: Support user defined post types (defaults are post, page, and product).
* Improvement: Change "Slurp" to "Yahoo! Slurp" and add SeznamBot, Pinterestbot, and UptimeRobot to the list of known bots for the do not log list.

## Version 1.8.2 (August 8, 2017) ##
* FIX: Verify that the daily cleanup cron job is running.
* FIX: Include post type "product" in the spell checker for compatibility with WooCommerce (fix part 1/?).
* FIX: Ignore characters -, _, ., and ~ in URLs when spell checking slugs (for ozzymuppet).

## Version 1.8.1 (June 13, 2017) ##
* Improvement: Add a new link and don't require a link to view the debug file (for perthmetro).

## Version 1.8.0 ##
* Improvement: Do not create captured URLs for specified user agent strings (such as search engine bots).

## Version 1.7.4 (June 8, 2017) ##
* FIX: Try to fix issue #19 for totalfood (Redirects & Captured 404s Not Recording Hits).

## Version 1.7.3 (June 2, 2017) ##
* FIX: Try to fix issue #12 for scidave (Illegal mix of collations).

## Version 1.7.2 (June 1, 2017) ##
* FIX: Try to fix issue #12 for scidave (Call to a member function readFileContents() on a non-object).

## Version 1.7.1 (May 27, 2017) ##
* FIX: Always show the requested URL on the "Logs" tab (even after a redirect is deleted).
* FIX: "View Logs For" on the logs tab shows all of the URLs found in the logs.

## Version 1.7.0 (May 24, 2017) ##
* Improvement: Old log entries are deleted automatically based on the maximum log size.
* Improvement: Log structure improved. Log entries no longer require redirects. 
This means additional functionality can be added in the future, 
such as redirects based on regular expressions and ignoring requests based on user agents.

## Version 1.6.7 (May 3, 2017) ## 
* FIX: Correctly log URLs with only special characters at the end, like /&.
* FIX: Fix a blank options page when a page exists with a parent page (for Mike and wdyim).

## Version 1.6.6 (April 20, 2017) ##
* Improvement: Avoid logging redirects from exact slug matches missing only the trailing slash (avoid canonical 
    redirects - let WordPress handle them).
* Improvement: Remove the "force permalinks" option. That option is always on now.

## Version 1.6.5 ##
* Improvement: Add 500 and "all" to the rows per page option to close issue #8 (Move ALL Captured 404 URLs to Trash).
* FIX: Correct the "Redirects" tab display when the user clicks the link from the settings menu.

## Version 1.6.4 (April 6, 2017) ##
* Improvement: Add a "rows per page" option for pagination for ozzymuppet.
* FIX: Allow an error message to be logged when the logger hasn't been initialized (for totalfood).

## Version 1.6.3 (April 1, 2017) ##
* FIX: Log URLs with queries correctly and add REMOTE_ADDR, HTTP_USER_AGENT, and REQUEST_URI to the debug log for ozzymuppet.
* Improvement: Add a way to import redirects (Tools -> Import) from the old "404 Redirected" plugin for Dave and Mark.

## Version 1.6.2 ##
* FIX: Pagination links keep you on the same tab again.
* FIX: You can empty the trash again.

## Version 1.6.1 ##
* FIX: In some cases editing multiple captured 404s was not possible (when header information was already sent to
    the browser by a different plugin).
* Improvement: Forward using the fallback method of JavaScript (window.location.replace() if sending the Location:
    header does not work due to premature outptut).

## Version 1.6.0 ##
* Improvement: Allow the default 404 page to be the "home page."
* Improvement: Add a debug and error log file for Dave.
* FIX: No duplicate captured URLs are created when a URL already exists and is not in the trash.

## Version 1.5.9 ##
* FIX: Allow creating and editing redirects to external URLs again. 
* Improvement: Add the "create redirect" bulk operation to captured 404s.
* Improvement: Order posts alphabetically in the dropdown list.

## Version 1.5.8 ##
* FIX: Store relative URLs correctly (without the "http://" in front).

## Version 1.5.7 ##
* Improvement: Ignore requests for "draft" posts from "Zemanta Aggregator" (from the "WordPress Related Posts" plugin).
* Improvement: Handle normal ?p=# requests.
* Improvement: Be a little more relaxed about spelling (e.g. aboutt forwards to about).

## Version 1.5.6 ##
* FIX: Deleting logs and redirects in the "tools" section works again.
* Improvement: Permalink structure changes for posts are handled better when the slug matches exactly.
* Improvement: Include screenshots on the plugin page, a banner, and an icon.

## Version 1.5.5 ##
* FIX: Correct duplicate logging. 
* Improvement: Add debug messages.
* Improvement: Reorganize redirect code.

## Version 1.5.4 ##
* FIX: Suggestions can be included via custom PHP code added to 404.php

### Version 1.5.3 ###
* Refactor all code to prepare for WordPress.org release.

### Version 1.5.2 ###
* FIX plugin activation. Avoid "Default value for parameters with a class type hint can only be NULL"
* Add a Settings link to the WordPress plugins page.

### Version 1.5.1 ###
* Prepare for release on WordPress.org.
* Sanitize, escape, and validate POST calls.

### Version 1.5.0 ###
* Rename to 404 Solution (forked from 404 Redirected at https://github.com/ThemeMix/redirectioner)
* Update branding links
* Add an option to redirect all 404s to a specific page.
* When a slug matches a post exactly then redirect to that post (score +100). This covers cases when permalinks change.
