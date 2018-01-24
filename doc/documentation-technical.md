# OpenBadgesFramework Technical Documentation

## Installation and Upgrades
1. Clone (or copy) this [repository](https://github.com/Badges4Languages/OpenBadgesFramework/releases) to the /wp-content/plugins/ directory
1. Activate the plugin through the 'Plugins' screen in WordPress

For upgrades, download the las stable version from github, delete from FTP the old plugin and install the new one.

## Setup the plugin

### Plugin settings

#### Change the badges issuer informations
OpenBadges need some key information for the delivery of the badge. Without that information, the plugin will not work:
* Site Name (by defaul, take the site name from ```get_bloginfo ('name')```)
* WebSite URL (by default, take the url from ```get_bloginfo ('url')```)
* Image URL
* Backpack account (by defaul, take the mail administrator frrom ) ```get_bloginfo ('admin_email')```)

![settings_profile](../readme-assets/settings_profile.png "Settings: profile")

#### Change issuer badges page links
The users have some shortcuts to make easy the process. Here are the place where the links to those shoutcuts are created:
* Change the role. Teachers can change the role from send badge page.
* Add class. Shortcut to creating a new Class from send badge page.
* Get Badge. Redirection page for users after opening the email (by default, OBF will create a page /get-badge-page/ after the first activation).

![settings_links](../readme-assets/settings_links.png "Settings: links")

![settings_links-get-badge](../readme-assets/settings_links-get-badge.png "Settings: selected page")

### Manage Roles and Cappabilities
--- https://es.wordpress.org/plugins/wp-user-groups/ ----

OpenBadgesFramework offer 3 types of roles with different cappabilities:
(Out of the box, the OBF roles is Subscriber as just the wordpress **Read** cappability is activated)
* Student
  * read (a WP cappability)
  * obf_send_self (allow to self send of badges)
* Teacher
  * read (a WP cappability)
  * obf_send_self (allow to self send of badges)
  * obf_send_single (allow to send a badge to one student at a time)
* Academy
  * read (a WP cappability)
  * obf_send_self (allow to self send of badges)
  * obf_send_single (allow to send a badge to one student at a time)
  * obf_send_multiple (allow to send a badge to multiple students at once)
  * obf_send_certificate (allow to send certificate badges)
  * obf_send_teacher (allow to send teacher badges)
  * obf_job_listing_integration (allow to save badges in classes where a class is a post of WP Job Manager)
  * obf_user (allow to see in the backend the user information)

  Also it use the default WP Addministrator Role for the settings of the plugin.

Out of the box, a new user can be (after the login) one of those roles (upon the configuration of the site) and later the administrator can change the role to a more related one.

![wp-settings_new-user-default-role](../readme-assets/wp-settings_new-user-default-role.png "New user default role")

If an automatization of the distribution of the roles are need it, RCP (or an alternative) must be activated and settup.

![rcp_subscription-levels-creation](../readme-assets/rcp_subscription-levels-creation.png "Subscription levels creation")

Remember to Asign a match to each [subscription level](http://docs.restrictcontentpro.com/article/1558-creating-subscription-levels) with the user role.

![rcp_subscription-levels](../readme-assets/rcp_subscription-levels.png "Subscription levels creation")

A select the role page is need it (can be free or paid upon the configuration).

The OpenBadgesFramework Settings page allow in **Links** to select the page where the [Register Form](http://docs.restrictcontentpro.com/article/1597-registerform) is created.

With a plugin of roles and cappabilities (like [Members](https://es.wordpress.org/plugins/members/ "Members plugin website")), the capabilities can be asign to another role and to use the functionalities.
![members-cappabilities](../readme-assets/members-cappabilities.png "Student Role Custom Cappabilities")



#### Student role
* Can receive badges and to keep the profile information.
* Can self issue a non certified badge.
* Can comment the class after receiving the badge.

#### Teacher role
The same as Student role plus:
* Can self issue a teacher badge.
* Can send a non certified badge to one student at a time.
* Save all the students information in one single class.
* Can answer the class Studens' comments.
* The profile can be delete but the Classes information can not ever be deleted.

#### Academy teacher role
The same as Teacher role plus:
* Can send a non certified badge to multiple students at a time.
* Can send a certified badge to one student/multiple students at a time.
* Can create multiple classes.

#### Administrator role
The same as Academy role plus:
* Can send certified teachers badges

## Creation of the Badges
OpenBadgesFramework allow the creation of two types of badges for the **Administrators** of the site. Normal badges and Certified badges.
* Normal badges can be delivery by any user of the site with Teacher Role.
* Certified badges are allowed just for teachers with the Academy Role.

For the creation of a badge is necessary:
* Name: The name of the badge.
* Description: A description for the badge.
* Badge Criteria: The Criteria for different languages (just the first one is used).
* Image: The feature image.

Other information is necessary for the correct integration with the site:
* Content: information about the badge.
* The Certification Type: Not certified/Certified.
* The Target type: Student/Teacher.
* Field of education: the subject of the class (The second language). Not used.
* Levels: the level of the class (Students: A1-A2-B1-B2-C1-C2; Teachers: T1-T2-T3-T4-T5-T6).

Optional:
* Description translations (WP Comments): A translation of the description.

## Sending of badges (Issuer page)
OpenBadgesFramework allow to issue badges in 3 ways:

Administrators have the same functionalities as Academy teacher role plus the issue of certified Teacher badges.

Those are the options before to send a badge:
* **Field of education**: the subject the student learn.
* **Level**: the level of the class.
* **Badge**: the badge name.
* **Description**: the Reference Level Descriptors of the badge. By defaul badges are in english, but translations are available.
* **Class**: the student class name with information such starting date and place (Just Academy teachers role). It requires [Job Manager](https://wpjobmanager.com/ "WP Job Manager plugin") plugin activated.
* **Mail**: the email of the student/s.
* Information:
  * **Addition information**: Some information that will be showed in the description of badge.
  * **Criteria**: Url of the work or of the document that the recipient did to earn the badge.

If the student have a badge and a teacher send the same badge again, no updates in the information of the database.

### Send badges pages

#### Issuer page Self mode
A student/Teacher/Academy teacher role can receive a badge as Student or Teacher (Non certified).

* Field of education
* Level
* Badge
* Description
* Information:
  * Addition information
  * Criteria

#### Issuer page Issue mode
A Teacher/Academy teacher role can send a Student Badge (Non-Certified).

An Academy teacher role can send a Student Badge (Certified)

* Field of education
* Level
* Badge
* Description
* Class (Just available if activate)
* Mail
* Information:
  * Addition information
  * Criteria

#### Issuer page Multiple issue mode
An Academy teacher role can send Multiple badges to Multiple students (Certified and Non-Certified).

* Field of education
* Level
* Badge
* Description
* Class (Just available if activate)
* Mail (Multiple)
* Information:
  * Addition information
  * Criteria

### Database

Open Badges Framework save all the badges information related to teachers and students with 2 [custom Database Tables](https://codex.wordpress.org/Creating_Tables_with_Plugins)

#### Users Database
```
id  userEmail
1   student@student.com
```
#### Badges Database

```
id  userEmail            badgeId   fieldId   levelId   classId   teacherId    roleSlug         dateCreation          getDate               getMobDate    json                                                                 info                                  evidence
1   student@student.com  140       1712      7                    1            administrator   2018-01-01 08:00:00   2017-12-18 09:00:00                 161499a421c21ea585cc025d04f0e3d439d6220451b22c820c62d4478fc6aaf0 	That is an example of information.    https://www.example.edu/student-list.php
```
##### Badges Database columns
* **userEmail**: The Earn user email.
* **badgeId**: The ID of the Badge the student receive.
* **fieldId**: The ID of the Field of education of the Badge.
* **levelId**: The ID of the Level of the badge.
* **classId**: If the Badge is inside of a Class, the Class id.
* **teacherId**: The issuer user ID.
* **roleSlug**: The role of the issuer.
* **dateCreation**: The date of the issue of the badge.
* **getDate**: The date of the earn of the badge.
* **getMobDate**: If the badge is transfer to Mozilla Backpack.
* **json**: The Json file name.
* **info**: The information the teacher write about the students.
* **evidence**: Is the link to an external url where the teacher can show an evidence of the badge (pdf with a list of notes, a site with students names...).
* **Json file**: By sending a badge, 3 Json file are created inside of the folder open-badges-framework > Json. The Json files remain forever (For now).

A file with the information about the website (just one file each installation):
issuer-info.json
```
{
  "name": "Site Name",
  "url": "http://site-name.com.com",
  "description": "Issue and Earn Badges.",
  "image": "http://site-name.com/wp-content/uploads/2017/05/badge-image.png",
  "email": "webmaster@site-name.com.com"
}
```
One Json file is the technical information about the badges

Example fo file name: badge-cc8197a1a66bd28d240934e16a895183f7a59e2285eb5e8b408ebba515ff
```
{
  "name": "Badge Name",
  "description": "FIELD: Field of education Name  \u2013  LEVEL: Level Name  \u2013  DESCRIPTION: Can understand and use familiar everyday expressions and very basic phrases aimed at the satisfaction of needs of a concrete type.\r\nCan introduce themselves and others and can ask and answer questions about personal details such as where he/she lives, people they know and things they have.\r\nCan interact in a simple way provided the other person talks slowly and clearly and is prepared to help.  \u2013  Additional information: That is an example of information.",
  "image": "http://site-name.com/wp-content/uploads/2018/01/badge.png",
  "criteria": "http://site-name.com/open-badge/badge-name/",
  "tags": ["Field Of Education Name", "Level Name"],
  "issuer": "http://site-name.com/wp-content/uploads/open-badges-framework/json/issuer-info.json"
}

```
One Json file is the des

Example of the file name: cc8197a1a66bd28d240934e16a895183f7a59e2285eb5e8b408ebba515ffa5dd.json
```
{
  "uid": "5a3272e5b6ffb",
  "recipient": {
    "type": "email",
    "identity": "student@student.com",
    "hashed": false
  },
  "badge": "http://badges4languages.com/wp-content/uploads/open-badges-framework/json/badge-cc8197a1a66bd28d240934e16a895183f7a59e2285eb5e8b408ebba515ffa5dd.json",
  "verify": {
    "url": "http://badges4languages.com/wp-content/uploads/open-badges-framework/json/cc8197a1a66bd28d240934e16a895183f7a59e2285eb5e8b408ebba515ffa5dd.json",
    "type": "hosted"
  },
  "issuedOn": "2018-01-01",
  "evidence": "https://www.center-name.edu/student-list.php"
}

```


## User profile
All the roles have a profile for tracking the information of the receive badges.

The save information in the profile is:
* Badge name
* Badge language
* Sender
* Comment

## Shortcodes
The frond-end fuctionality can be use in any page with the shortcode ```[send_badges]```.

If we need to show just one of the 3 types of the send badges subpages, we can use the following shortcodes:

* ```[send-badge form="a"]```: for self send of the badge.
* ```[send-badge form="b"]```: for send the badge to one user at a time.
* ```[send-badge form="c"]```: for send the badge to multiple users at a time.





## Customization
Appearance

Modify Colors and More

Booking Service Integration

Book Tables, Services, and More

Customization Code Snippets

Collection of Code

Child Themes

Advanced Customization Techniques

Translations

Change Text and Words





---
Back to [Readme](../README.md).
