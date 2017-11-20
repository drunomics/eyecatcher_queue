### Eyecatcher Queue

The eyecatcher Queue is a configurable list of entities which act as 
eyecatchers. The queue can be used in a view to pull from the list of 
eyecatchers and inject them dynamically into the view result 
(e.g. in a teaser stream).
 
The eyecatcher entities themselves (Paragraphs, Blocks, etc.) have to be
created & themed outside of this module.

#### Setup

Create a new entityqueue of type `Eyecatcher queue` and configure which 
entities should be part of the queue (e.g. Paragraphs of a specific type).

You can select inline entity form complex for the entries in the form display, 
since this module is written with that in mind, but you may as well use a 
autocompleter instead.

After you created your queues, don't forget to set up the permissions for each
one (`update {subqueue} entityqueue`).

The queues can be configured via `Admin > Config > Content > Eyecatcher`

To make use of a queue, one can leverage the provided Views area plugins.