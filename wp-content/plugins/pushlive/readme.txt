=== PushLive - Staging Sites to Live in One Click ===
Contributors: jxaxmxixn, John Jin (Older Joomla)
Tags: PushLive, 1 Squared, Stage, Staging, Staging Site, Stage to Live, Duplicate, Duplication, Replicate, Replication, Multisite, Multi Site, Multi-site, Network Sites, Multisite Network, Migrate, Migration, Backup, Clone, Cloning, Copy, Duplicate Site, Clone Site, Backup Site, Development Site, Deploy, Deployment, Sync 
Requires at least: 4
Tested up to: 4.3.1
Stable tag: 0.6.8
License: GPLv3 or later


Allows you to have a fully functioning development Staging Site or Multisite that you can individually Push to Live when ready.

== Description ==


**PushLive allows you to have a fully functioning staging site or Multisite environment for editing and development that you then with a single click individually push to the live site when you're ready.**

**Now works with Multisite!** - Featuring Independent Pushes for Each Site

**Coming Soon (Next Major Release): PushLive Replicate** - Easily create a staging site from your current live site

<blockquote>
<h4>With PushLive you ( or your clients ) will make all initial and future edits on the staging site, push them to the live site, and in most cases never actually touch the live site except to enjoy its awesomeness</h4>
<h4>&nbsp;</h4>
<ul>
<li>
Please read the <a href="installation#sections">Installation and Setup Instructions</a>
</li>
<li>
Single Site WordPress installations - PushLive is compatable with nearly all available Plugins
</li>
<li>
Special Multisite WordPress installations - PushLive is installed at the Network Admin level and best used when building and testing the site from the ground up because some Plugins are simply not compatable with PushLive in a Multisite environment 
</li>
</ul>
</blockquote>

**Requires:**

 * Linux based server


**Major features in PushLive include:**


 * Fast staging to live pushes that only update the new or changed content as necessary.
 * Individual and Independent pushes for each site if using Multisite.
 * Easy 1 page, top to bottom setup and configuration.
 * A visible log of all previous pushes can be viewed on the main PushLive page.
 * Require all users to log in to view the staging server 

**Other Features:**

 * Creates database backups during every push

**History:**

 * This was originally a simple tried and true Joomla 1.5 component my company developed many years ago
 * I then updated it for Joomla 2.5 and added some new features
 * Updated it for Joomla 3.5 and again added some new features
 * Rewrote it for WordPress for my own use about a year ago
 * Decided shortly after that I would become a WordPress developer and release it publicly
 * 9 months of using it later I finally released it with some new features and minor bugs fixed
 * Historically PushLive has always served us/me good use so it should be something you can trust and rely on as well
 * We have NEVER had a major issue with PushLive we had to recover from, but you should always back up your site


**Banner Imagery:**

* Photo by: Tom Johnson (https://www.facebook.com/tigger1759)


== Installation ==

**NOTE:** PushLive at it's current state is geared for those who understand basic Linux file path structure, WordPress database tables and how they might relate to various plugins and WordPress features.

 * If you have any issues at all please use the Support Forum to quickly get them solved, you may contact me directly via jamin@1squared.com but you should first start a support thread in the forum.

**For Multisite - install this plugin at the Network Admin level**


<blockquote>
<h3>Recommended Setup</h3> ( You can use other methods if you're knowledgeable )
	<ul>
		<li>
			<h4>Step 1:</h4>
			Get a Domain name [ example.com ] and then create a subdomain [ stage.example.com ]  (stage. is just a suggestion, you can use anything)
		</li>
		<li>
			<h4>Step 2:</h4>
			Create 2 folders on your web server ( example: /var/www/example.com/stage & /var/www/example.com/live ) - your actual paths will vary based on your host or other factors
		</li>
		<li>
			<h4>Step 3:</h4>
			Point example.com to the "live" folder and stage.example.com to the "stage" folder
		</li>
		<li>
			<h4>Step 4:</h4>
			Create 2 databases,  1 for stage and 1 for live - name them example_stage and example_live ( for example ) and make sure you have a full rights user for each of them ( can be the same user for both )
		</li>
		<li>
			<h4>Step 5:</h4>
			<ul>
				<li>
					a: Install WordPress in your stage folder using your stage database credentials
				</li>
				<li>
					b: Install WordPress in your live folder using your live database credentials
				</li>			
			</ul>
		</li>
		<li>
			<h4>Step 6:</h4>
			Install PushLive on your staging site only and then configure it with the proper credentials and paths
		</li>
		<li>
			<h4>Step 7:</h4>
			Perform your initial PushLive push as per the table instructions within the PushLive Setup page ( check all the boxes suggested ) - verify it pushed to the live site and is working properly - then go back to PushLive Setup and uncheck any tables that the live site will use to update data
		</li>
	</ul>
</blockquote>

**Remember:** If you're losing any data on the live site after a push, it's likely because the table that handles that data is still checked in the stage PushLive Setup

**Multisite:** You need to do an individual initial push for each one of your Sites for them to be visible on the live side.

**Very Important - Understanding Tables:**

<a href="https://codex.wordpress.org/Database_Description#Table_Overview" target="_blank">Learn More About WordPress Tables Here</a>

 * Tables are the most thought intensive part in this entire process (especially if you're using a lot of Plugins)
 
 * If your site is relatively simple with few added Plugins this will be fairly easy to set up and maintain
 
 * With more Plugins, things can start to get a bit more complex and require more initial setup and maintenance attention
 
 * If you follow my suggestions no matter how complex your site is, and once you get everything working the way you want, all you ever have to do from there is use the PushLive button without worry.

 * The good news is that if your newly added Plugin doesn't save any data from user interaction on the live site ( user comments, votes, hits, etc... ) you can just check all of the tables it adds to the PushLive Setup list ( if any at all ) and be done with the thought for that Plugin.

 * You'll have to do your part to determine each Plugins data use - you'll have to understand that some Plugins just might not work with PushLive ( most should ) - you'll have to understand some Plugins might require you to NOT update them past a certain version ( you may have to revert back to a previous version in the case that a Plugin update then breaks your site - this is a case by case basis with individual Plugins ). It's always best practice to make sure your site is getting backed up often so you can easily revert back if anything unwanted does happen.

 * The suggestion for anyone is to start with a fresh WordPress copy and then as you add Plugins one by one, take note of the new tables they individually add to the PushLive Setup list ( if any ).

 * Usually you'll first want to put a check-mark on all the tables this new Plugin creates and then perform a PushLive push before then determining what tables then need to be unchecked.

 * If this newly added Plugin interacts with the users of the live site and saves any important data from that user, you'll want to figure out what tables are responsible for holding this data and then uncheck that individual table as necessary ( a plugin might have 1 or more tables you'll have to uncheck ). This can be a slightly tedious back and forth testing and re-pushing phase but once you have got it figured out you can relax for that Plugin.

 * You'll have to remember this for each new Plugin you install taking note that some Plugins might add or change tables as the Plugin is updated. Best practice is to pick a day to update all your Plugins, perform a site backup first, then make the updates and do your individual testing with each updated Plugin - or to set your mind at ease simply get to a comfortable point with all your Plugins and don't update them for a year or a few years at a time ( when you're ready to do major overhauls or changes ).

 * If you have a really simple site, don't let all of this confuse you too much.  If your site is complex your plan of action should also be well thought out.
 


== Frequently Asked Questions ==

**Q: Does PushLive work with Multisite?**

**A: Yes, as of Version 0.6 PushLive is officially Multisite enabled.**


**Q: Can you independently push individual sites in Multisite?**

**A: Yes, each site is independently updated with its own PushLive Now Button.**


**Q: Does PushLive work with all plugins?**

**A: Yes and No - as with any WordPress Plugin you'll simply have to test each one to be 100% sure**

 * Regular WordPress - Nearly all Plugins should work, though some special case Plugins might not be compatable
 
 * Multisite WordPress - If you install a Plugin directly to an individual site the Regular WordPress rules above should apply, however you're going to have to be more choosy with Multisite Plugins as some do not store data in a way that is compatable with PushLive


== Screenshots ==

Updated Screenshots Coming Soon...

1. Main PushLive window just after a successful push.
2. Setup window exactly as we have it on our 1 Squared site (please don't hack us ;)



== Changelog ==

= 0.6.8 = 

 * Corrected field name issue that was preventing some database replaces from replacing everything
 * Corrected proper database password issue
 * Added better installation instructions

= 0.6.7 = 

 * Allowed Slashes in URL's

= 0.6.6 = 

 * Adjusted Visual Indicator for Save Buttons

= 0.6.5 = 

 * Addressed Save Issue That Affected 'Sub-directory' Installs Only
 * Added Visual Indicator to Save Buttons

= 0.6.4 =

 * Changed File Synchronization Settings (More Forgiving)
 * Localized Base Domain Replacing to Selected Tables Only (especially for issues in Multisite)
 * Corrected Issue Preventing Setting Save (issue only in 0.6.3)
 * Added Sneak Peak for the New 'Replicate' Feature

= 0.6.3 =

 * Added PushLive to Network Admin Bar for Multisite
 * Added Simple Database Connection Test Button
 * Added Minor Client Side Script to Remove http:// https:// From Domain Related Textboxes
 * Minor CSS Changes to Improve Appearance

= 0.6.2 =

 * Adjusted Code for Compatability with Earlier PHP Versions

= 0.6.1 =

 * PHP Version Testing Feature

= 0.6 =
*Release Date - 25 October, 2015*

 * Major Upgrade for WordPress Multisite
 * New Settings for Multisite
 * New Multisite Features
 * Removed the Popup Dialogue Confirmation on Pushes
 * Added Individual Site Pushes for Multisite Installations
 * Various Minor Changes

= 0.5 =
*Release Date - 30 September, 2015*

 * Minor Enhancements
 * Plugin Ranking

= 0.4 =
*Release Date - 27 September, 2015*

 * Added a donate button
 * Minor not bug related changes

= 0.3 =
*Release Date - 27 September, 2015*

 * Fixed issue where the file sync was erring out due to it not being able to set the time and date on files
 * Added various help instructions and suggestive placeholders throughout Setup page

= 0.2 =
*Release Date - 16 July, 2015*

 * Backup Directory now requires user input to choose desired location
 * Removed PHP Error Reporting
 * Changed file_list.txt to .file_list
 * Added ability to replace all staging URLs with live URL in entire live database (because literally hundreds of cases were found in various tables)
 * Added Settings Link to WordPress Plugins page
 * Added links to PushLive support page
 * Added index.php everywhere
 * Fixed/changed many other minor things

= 0.1 =
*Release Date - 14 July, 2015*

 * Adapted from our previous working component to work as a new WordPress Plugin
 * Some features are not yet visible but are partly working in the background ready for future releases
 * More updates and features are coming!
 * Only tested on WordPress version 4 so far, though it may likely still work on earlier versions.




== Upgrade Notice ==

**Please make sure your WordPress installation is up to date to ensure proper PushLive functionality**



