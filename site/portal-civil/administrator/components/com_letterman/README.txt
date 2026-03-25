***************************
******* Letterman *********
Simple Newsletter Component
***************************

License: GNU / GPL

Authors: 
* Soeren Eberhardt <soeren@mambo-phpshop.net>
    (who just needed an easy and *working* Newsletter component for Mambo 4.5.1 and mixed up Newsletter and YaNC)
* Mark Lindeman <mark@pictura-dp.nl> 
    (parts of the Newsletter component by Mark Lindeman; Pictura Database Publishing bv, Heiloo the Netherland)
* Adam van Dongen <adam@tim-online.nl>
    (parts of the YaNC component by Adam van Dongen, www.tim-online.nl)
-------------------------

Installation:

Just upload the zip-archive with Mambo's Component Installer.


Newsletter Management:

Just go to "Letterman" => "Newsletter Management" and create a new Newsletter.
Send it by clicking on "Send To" in the Newsletter List. After that you will see a confirmation page
where you can select a group of recepients to send the newsletter to.

Images in the HTML Message will be COMPLETELY embedded into the Email. 
Please be aware of the fact that images grow in size when emebedded in emails because base64 encoding.


Subscriber Management:

Just go to "Letterman" => "Subscriber Management". There you can add, edit and delete subscribers.

You can also Import YaNC / MaMML Export Lists. Note that Letterman doesn't use the "receive_html" field.
(Users that can't read HTML, will automatically see the alternative Text body. That's not dramatic.) 
But because of that you will probably not be able to use Letterman Subscriber Export Lists with the YaNC / MaMML
Import Feature.


Facts you better know of:
* all Images in HTML will be embedded into the email, yes even when they are remote Images.
* You can use a "subscribe module" for Letterman, which shows a simple subscription form. Do
  not use YaNC, MaMML or other subscribe modules. they won't fit...
* Letterman has nearly no configuration, it shall be simple, but working.
* Letterman uses the default Mambo Mail configuration - either mail(), sendmail or smtp sending.
* Letterman doesn't allow you to manage multiple Mailing Lists - most users don't need that feature
* when sending, one email will be sent to the site owner, all other recepients are "BCC".
* you can configure some email texts / footers in /administrator/components/com_letterman/language/english.messages.php


That's all.
thanks to Mark Lindeman & Adam van Dongen for the work on their components, this component is based of.
