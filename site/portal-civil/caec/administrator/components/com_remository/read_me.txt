<----------->
3.25 - release
10 March, 2006
Martin Brampton
<----------->

Fix possible SQL injection vulnerability

Added language files for Bulgarian, Italian, Russian and Traditional Chinese



<----------->
3.24 - release
22 November, 2005
Martin Brampton
<----------->

(Release 3.23 was a custom version for a client)

Fixed recently reported bugs:

Initial defaults not valid for MySQL 5.

Pass by reference errors.



<----------->
3.22 - release
4 October, 2005
Martin Brampton
<----------->

Fixed recently reported bugs:

1) Error passing array slice by reference

2) Upload log entries not recording user correctly.  Also changed logging so that
file size values are always in Kb.

3) Error in converting file date for display

Also, extra languages: Arabic, Finnish.


1<----------->
3.21 - release
21 September, 2005
Martin Brampton
<----------->

Fixed recently reported bugs:

1) When the file sequence was selected to be different from by name, the first
page displayed correctly, but subsequent pages lost the ordering.

2) Failure in the database upgrade when single quote found in fields.

3) Error in database upgrade so that old files in categories or folders meant to be
restricted to registered users were accessible, even though the category or folder
had the correct attributes.

4) Installation problems with Mambo 4.5.3 beta.


<----------->
3.20 - release
26 August, 2005
Martin Brampton
<----------->

Modified the search function so that it honours restrictions on who can see what.

Added functionality to the admin "List Missing Files" function so that it also updates the
database with the latest file date and file size for all local files.  (This accounts for
any refresh of the file via FTP etc).

Included utility code for use by the companion modules so that it is easier to maintain.
The actual modules now contain much less code.  The GRAPHitory component also uses this
common code.  If the presence of SEF is detected, the modules now avoid accessing the
database to unnecessarily find out the Itemid.

More languages are now available: English, Czech (two versions), Dutch, Norwegian, Polish,
Portuguese (and also Brazilian Portuguese), German (informal and formal), French, Spanish
and Simplified Chinese.  Some have not yet been fully translated for version 3.20.

WARNING - the installation attempts to replace the missing icons for "Approve" that used
to be part of Mambo and were for some reason removed with version 4.5.1.  In some instances
this will not work and an error message is generated.  It depends on the directory
permissions and ownerships.  If necessary, you should manually copy the Approve images
from the distribution zip package to /administrator/images.

Please see the installation instructions at:
http://www.remository.com/view/ReMOSitory/ReMOSitory_basics/Upgrading_to_3.20/

Please also see general documentation (some of it now out of date) at:
http://black-sheep-research.com/wiki/bsrwiki.php

Please refer queries to martin@remository.com

TO KEEP UP WITH REMOSITORY DEVELOPMENTS VISIT http://www.remository.com REGULARLY


<----------->
3.20 beta 9
22 August, 2005
Martin Brampton
<----------->

Significant errors fixed:
1)Add file from admin interface, specified by URL.  Was failing with message about file updates
that require the file to be moved.
2) Visitor uploads (where permitted) were failing to happen as a result of a still too stringent
test for valid uploads.

File date has been changed to a DATETIME from just a DATE.  This allows the companion modules to
use file date rather than submitted date, yet still discriminate between uploads received on the
same day.  The modules will be modified and released to match.

The ID is now displayed against categories in the admin side, for convenience with facilities
such as the modules or Quickdown.

Further checks to avoid PHP Notices in relation to attempts to circumvent the download mechanism
and its anti-leech mechanisms.

Changed default dates to valid dates to meet restrictions in MySQL 5 (although version 5 is not
really supported by Remository, as it is not yet the recommended GA release.  Most testing is
done with MySQL 4.1, with care being taken to try to avoid incompatibilities with earlier versions
that meet the Mambo minimum requirements).


<----------->
3.20 beta 8
16 August, 2005
Martin Brampton
<----------->

Further changes to resolve issues around the upload of files into various directories, and
their inclusion via FTP to different directories.

Corrected error that disabled group handling, following the earlier changes to avoid reference
to the groups tables when the groups component not installed.


<----------->
3.20 beta 7
11 August, 2005
Martin Brampton
<----------->

Fixed errors preventing orphan files in path specified for category/folder being integrated
into the file repository.  Fixed problem of submitter not being picked up for admin side
operations.


<----------->
3.20 beta 6
8 August, 2005
Martin Brampton
<----------->

Much more information provided in the admin side lists - "List Categories", "List Folders"
and "List Files".

Download module has two tests removed.  The check that Remository had adequate permission
to the file for download has been removed because it is not reliable on all systems and
could prevent the download even though it was possible.  And the check that the URL for a
remote file could be read has been removed become some sites have this PHP operation barred.

Eliminated all reference on Mambotheme Groups where not installed.  Check on multiple voting
was not working - fixed.  Visitors now get link to submit files (unless there is an overriding
problem).  They may then get a message saying that there is nowhere to which they are permitted
to upload.  This may be made a bit slicker in future, but is adequate for now.

<----------->
3.20 beta 5
4 August, 2005
Martin Brampton
<----------->

** URL checking completely changed since all attempts at full validation proved incompatible
with some environments. The checks are now purely on format, either a standard URL or a URL
based on IP address rather than domain name. (** I made a mistake in implementing this in
beta 4 - one character wrong - so all my checking with Regular Expression tools was in vain).

Correction to stripping of backslashes which was overdone - particularly a problem in Windows
environment. Remository 3.20 should handle "difficult" characters such as single quote correctly,
and should function regardless of "magic quotes" settings. Please report any issues with as much
detail as possible.

Error in admin facililty "Reset download counts" corrected; categories/folders now published
by default on creation; small improvements to "List missing files" including the change to
URL checking; tidier presentation after file upload; admin exempted from per day limit on
uploads; file title included in upload notification email; internal improvements.


<----------->
3.20 beta 3
28 July, 2005
Martin Brampton
<----------->

Third public beta of a major new version of ReMOSitory.  Still couldn't get the dates
right and made a slip throughout on the format string.  Most wrong in the download
module, leading to wrong log entries.  Simplest fix is to make a new release, so here
it is!

<----------->
3.20 beta 2
18 July, 2005
Martin Brampton
<----------->

Second public beta of a major new version of ReMOSitory.  Two major areas of fixes:

1) Dates were all over the place.  I attempted to use MySQL timestamps, but variation
in the implementation through different versions was causing chaos.  So the timestamps
have all been turned into DATETIME and are set by the code.

2) Uploading files linked by URL.  In order to give the best possible diagnostics,
there was a check in beta 1 that the URL could be opened.  However, it is possible
for the PHP configuration to disable this function, so in some cases it was failing
incorrectly.  This beta version checks that the URL includes a name that can be
resolved to an IP address.  It still excludes such things as specification by IP
address - maybe that ought to be a hack, or maybe the validation should change yet
further?  Comments?

<----------->
3.20 beta 1
18 July, 2005
Martin Brampton
<----------->

First public beta of a major new version of ReMOSitory!  Completely redesigned database
structure.  A number of new features (see below).  WARNING - THIS IS A BETA VERSION AND
MAY CONTAIN FAULTS.  Feedback is wanted!  Please read the installation instructions
carefully:

If you are running Remository 3.x AND IT WAS INSTALLED rather than being upgraded by copying
the program files, then you can simply uninstall it without damage to your existing database
tables.  Then install this version.  If the last ACTUAL INSTALL was done with a 2.x version,
then follow the procedure for 2.x.

If you are running Remository 2.x then you should first download and install the Remository
Manager component.  That is a purely admin side component that will provide you with some
useful utility functions.  Choose the option that removes a version 2.x Remository without
damaging the database tables.  Then you can install this version.

In any situation where you have existing database tables, the first thing to do after the
installation is to select from the admin Components drop down menu the Remository item and
the Convert pre-3.20 database sub-option.  This will convert your database, moving data into
new tables.  It will list any files that were not transferred (because they were not found).
The conversion can be run any number of times, but it wipes out previous entries in the new
tables.  It does not touch the old database tables, so it is possible to uninstall this version
and install an earlier 3.x Remository.  Obviously, any changes made with this version will be
lost in that case, but it does make the upgrade as safe as possible.

Please remember that any installation of Remository will reset the config to the default,
so you should always check your config options through the admin interface.  (This will
be avoided in a future version when the config will be preserved).

If you did not have a Remository installation at all, you can simply install this as a
standard Mambo component - look for Mambo documentation on component installation if you
have any difficulties with this.  After the basic install, remember to install at least
one category and link Remository via a menu for access from the front end.

You may also wish to install the Mambotheme Groups components (again purely admin side) if
you want to control access to the file repository by group membership.

PLEASE GIVE FEEDBACK on how you find this new version - for your own and other people's
benefit!

New features:

* It is now possible to specify by category/folder what level of access is available to
registered users and to visitors.  It can be none, upload only, download only, or both.

* In addition, there is a general admin option to specify whether users should see
categories and folders that they cannot download from, or whether they should be hidden.
There is a separate admin option to determine whether users can see files they cannot
download.

* The ability to specify a file path for the files belonging to a category or folder.
This should be used sparingly - please read the ReMOSitory road map before making use
of this new feature.

* ReMOSitory now makes a much sharper distinction between local files (hosted on the
web servers) and remote files (where a URL is specified instead).  For local files,
the physical name of the file is shown in admin pages, whereas for remote files the
URL is shown.

* A log table is added to the database, and every download or upload is recorded there,
along with votes for files.  Analysis tools will become available soon.

* Database tables now have a selection of carefully designed indexes that should make
ReMOSitory operate well even with large numbers of files.  The structure is optimised
strongly towards file browsing, slightly less strongly towards downloads, and penalties
are accepted on new additions (the least frequent operation for most sites).

* The WYSIWYG editor is used for the file description to expand the layout possibilities.

* Page control is improved on the admin side

* In the code there is now little distinction between categories and folders - they are
both objects called containers.  The only difference now is that a category is a
container that has no parent, while a folder is a container that does have a parent.
Any feedback on what terminology should be used outside the code will be welcome.
Folders continue to be capable of being nested to an arbitrary depth.

* There is a ReMOSitory search bot available that meshes with the standard Mambo site
search.  The full functionality of the Mambo site search (any word, all words, exact phrase)
is implemented provided the database is MySQL 4+.   Otherwise, all searches will be for
any word whatever is specified.

* ReMOSitory is now designed to work whether or not magic quotes is set on.  It is also
designed to work with "register globals" off (the preferred setting for security).
It should work with all versions of PHP from the earliest supported by Mambo 4.5 to PHP5.
It has been tested with MySQL 4.1 as well as earlier versions.

* The code continues to be highly OO, and has been further restructured for power and
efficiency.  This makes ReMOSitory an ideal starting point for those wishing to create
their own custom built file repositories.   Or for those that would like to commission
custom versions from us here - please contact us to discuss projects.

* Generated HTML is largely valid XHTML transitional.  Please report on any problems.

* ReMOSitory continues to support files with awkward names, such as including spaces or
quote marks - please report any problems.


<----------->
3.07
18 July, 2005
Martin Brampton
<----------->

Fixed one or two obscure problems.


<----------->
3.06
29 June, 2005
Martin Brampton
<----------->

Fixed a handful of obscure problems.  The most important is that when the file downloads
directory was not writable by Remository, entries could be created in the database that
would cause the admin file list function to crash.


<----------->
3.05
3 June, 2005
Martin Brampton
<----------->

DON'T FORGET there is Remository documentation to be found via the main menu at
http://black-sheep-research.com

This is a minor bug fix release.  The following have been fixed:
Check on file extension not applied on the admin side for files hosted elsewhere via URL.
Thumbnail image width and height values are omitted from the HTML if zero in config.
Code for handling submissions by URL on the user side modified to give correct diagnostics.
Removed the memory eating download code that somehow crept back into the last release.


<----------->
3.04
19 May, 2005
Martin Brampton
<----------->

NOTE: On line 124/5 of the language file, there is code to link to registration, so that
someone who runs into a block trying to access files that are restricted to registered users
is encouraged to login or register.  "Register" is a link that goes to the standard Mambo
registration.  If you are using some alternative registration mechanism to integrate with
other software, then the link needs to be edited in the language file.

LANGUAGE FILES: Now included are English, Dutch, Norwegian, Polish, Spanish and German, with
more on the way.  PLEASE NOTE that there are formal and informal versions of the German
language file, and you need to rename one of them (choose either germanf.php or germani.php)
to german.php.

A few more features:

i) By request, the header image on the Remository front page is now completely optional
and if it made null in the configuration file, no HTML will be generated for it.
ii) Likewise, if the language file is altered to make the heading null, then it is not
displayed and there is no relevant HTML around it.
iii) If both of the above are suppressed, even more HTML is removed.
iv) Messages about file submission being disabled for various reasons are now suppressed
if user submission is disabled in the configuration, UNLESS the current user is admin.
v) For admin operations involving selection of a category or folder, the select list is
now ordered more structurally, with folders shown immediately after the parent category,
and subfolders shown immediately after their parent folders, and so on.  Asterisks indicate
the depth of nesting.
vi) Files that are not uploaded, but linked via a URL, are not checked for acceptable file
extension, since the security issue is outside our control.

and error corrections:

i) If an upload fails, diagnostics were not being generated very intelligently - should be
more useful now
ii) Obscure errors fixed hopefully before anyone noticed
iii) Extra CR or similar removed from end of Dutch language file



<----------->
3.03
15 May, 2005
Martin Brampton
<----------->

Mainly released to deal with two issues:

i) This release introduces page control for the lists of files within a category/folder.
Without page control, it was impractical to hold a large group of files in one category
or folder - this restriction is now removed.  If anyone wishes to vary the working of
the page control, there are two DEFINEs.  In remository.php there is a DEFINE of _ITEMS_PER_PAGE
which by default is set to 10.  In remository.html.php there is a DEFINE of _PAGE_SPREAD, set
by default to 9.  Unless you have a very large filebase, this will not affect you.  It controls
the number of pages before and after the current page that have direct links via a clickable
page number.  So a maximum of 19 pages are shown at the default setting.

ii) Many people have the PHP option for register globals set on.  Remository used not to work
correctly if it was set off.  With this release, that restriction no longer applies.  Remository
is coded to work with the specific PHP global variables that contain POST and REQUEST values etc.

If you are running a large filebase, you should look carefully at what INDEXES exist for the
Remository tables.  This release of Remository will set more indices than earlier versions, but
this is only effective if you do a full install rather than an upgrade.  There is an item in the
Remository Documentation at http://black-sheep-research.com discussing database indices that is
worth reading if you have a lot of files.  You can add indices using PHPMyAdmin or similar.

In addition a few errors that could cause warning messages have been eliminated.

Language files included in the standard package are currently Dutch, Norwegian, Polish and Spanish.
German is also available at http://www.mamboportal.de/t9973-remository-v30v301-(sprachfiles).html.
The package includes a document explaining the changes to the language files since version 2.x of
Remository.


<----------->
3.02
3 May, 2005
Martin Brampton
<----------->

More fixes and tidying up in response to reports:
Coding error that in some circumstances produces a SQL error on the Remository front page.
Option to set uploads maximum to zero, meaning that no check is done on max uploads.
Note that the Remository forum (go to http://www.black-sheep-research.com) now has a pointer to
	formal and informal German language files.


<----------->
3.01
2 May, 2005
Martin Brampton
<----------->

More fixes and tidying up in response to reports:
Modifed code to work when PHP register globals is set to off.
Corrected a problem with icons when Mambo is in a subdirectory.
Added improved "approve" images for transfer into the administration/images/ directory.
Added automatic install of Dutch language file - others to follow as available.
Fixed problem with reset file counts when folder/category name contains single quote.
Corrected inconsistencies in date submitted.
Code tidying to move almost all database access into remository.class.php.


<----------->
3.0
30 April, 2005
Martin Brampton
<----------->

A few fixes and a bit of tidying up.  The functionality is described below.

For documentation (partial but improving) or for a support forum, please visit
http://www.black-sheep-research.com


<----------->
3.0 Beta1
18 April, 2005
Martin Brampton
<----------->

New Features
------------

* Auto-approve for admin and/or users via the front end - one button publishing of files
* File information page automatically places description in metadata (Mambo 4.5.1+ only)
* Admin option to suppress votes for files
* File count problems significantly improved
* Page control on file lists set to 20 items by default
* Files will normally be deleted from the repository as well as the database
* Improved W3C compliance, especially on the user side
* Pathway shows route through categories/folders
* If file home page is entered, then it will be displayed as a link
* Numerous minor fixes

The software is now highly object oriented, and largely rewritten.  This will form a basis for more
features to be added in future releases.  Please record your wishes through either
http://www.black-sheep-research.com/ or http://mamboforge.net/projects/remository.

WARNING
-------

This software has been tested on a limited range of configurations by the author and a select group
of brave individuals.  It is not thought to be dangerous to existing installations.  HOWEVER, there
is no guarantee that it does not contain faults that could be damaging to your existing installation.
Please remember that this is beta software, with very extensive changes since the last release.

Documentation
-------------

Documentation for Remository is being created at http://www.black-sheep-research.com, where you will
find "Remository documentation" on the main menu.  It is a WIKI and you are invited to contribute to
it if you are able to do so.

Upgrading from 2.x
------------------
- At this point, there are no database changes

- Settings File change - visit the admin Remository Configuration option and review new settings:
Date Format - as used by the PHP date function e.g. d M Y will give date format 18 Apr 2005
Default Version - will be used as the default version for new files
	(NB. trailing zeroes are liable to be lost if the field is numeric, so 1.00 will just finish as 1)
Allow Votes - if set to NO will suppress both the display of evaluation votes and their entry
Enable Admin Autoapp - if set to YES, new files submitted via the front side by ADMIN will automatically
	be approved and published without further action.
Enable User Autoapp - if set to YES, new files submitted via the front side by a registered user will
	automatically be approved and published without further action.
Enable List Downloads - if set to YES, a download link will be included against each file in the lists
	of files by category/folder.  If set to YES, a thumbnail image will also be clickable for download.
Allow Users to submit remote files - if set to YES, the full file submission dialogue is provided,
	including the option to supply a URL instead of uploading a file.  If set to NO, the user is given
	only the simpler form needed for files to be uploaded to the web server.  It is now a single action,
	the two stage approach is superseded.

- File Upgrades necessary :
-------------------------

/components/com_remository/remository.php
/components/com_remository/remository.html.php
/components/com_remository/com_remository_startdown.php
/components/com_remository/remository.class.php

/components/com_remository/language/english.php

/administrator/components/com_remository/admin.remository.php
/administrator/components/com_remository/admin.remository.html.php
/administrator/components/com_remository/read_me.txt
/administrator/components/com_remository/remository_install.xml

OR Carry out a complete installation using the usual Mambo component installer
------------------------------------------------------------------------------

NOTE the installation for 3.0 has been changed, so that if Mambo is later deleted from the system, the
database tables will NOT be removed.  And on installation, Remository 3.0 does NOT delete existing tables,
creating new ones only if they do not already exist.  BUT there is still a problem, since all older
versions of Remository WILL delete the database tables if they are removed, and you cannot install a
new version of Remository without deleting the old version.  This is a Mambo restriction.

The change will make future upgrades easier, although unfortunately it means that manual deletion of
tables is needed if you wish to totally remove Remository.  The Mambo component installation process
is not yet flexible enough to completely resolve the problem of how best to tackle installation, upgrade
and removal.

For the moment, if you have an existing installation and would like to do a full install, the only way
is to back up the tables (they all have table names starting with "xxx_downloads" where xxx is mos or
whatever alternative prefix you have chosen).  Removal will delete the tables from your database, and
the safest course is still to install the new Remository, then restore the database tables.

*******************************************************************************************

All Remository source code and documentation is strictly copyright (c) Martin Brampton 2005

*******************************************************************************************
