..  Editor configuration
	...................................................
	* utf-8 with BOM as encoding
	* tab indent with 4 characters for code snippet.
	* optional: soft carriage return preferred.

.. Includes roles, substitutions, ...
.. include:: _IncludedDirectives.rst

.. _start:

==================
Placements Service
==================

.. only:: html
  :Classification:
    placements

  :Extension name: 
    Placement Service
  :Author:
    |author|
  :Extension key: 
    |extension_key|
  :Version: 
    |release|
  :Description: 
    manuals covering TYPO3 extension "Placement Service"


.. toctree::
	:maxdepth: 5

	ProjectInformation
	Configuration/Index


======================================
Placements Service: Manage job offers.
======================================

What does it do?
=================
Placements Service aims to be a complete and comfortable tool for managing job offers and applications.
It suitable for small and medium sized companies as well as for bigger organizations or employment service provider.

Manage Positions
''''''''''''''''
Positions are job offers. Jobs offers can be managed through the TYPO3 backend or the frontend. They are displayed in list and single view. 
Positions can be categorized by many criteria like position type, fixed term, sector, working hours and so on.
They also feature a category field which allows to set arbitrary system wide catgories.

A configurable quick menu allows filtering the list view by any of the above criteria. It also features a radius search for a given location.

Manage Organizations
''''''''''''''''''''
Organizations offer jobs.
Users can list, show, add, edit and delete organizations both in frontend and backend.

Map View
''''''''''
List and single view of information can be configured to show a (Google) map view. 
Information about the geographic location is automatically retrieved from any record on creating or editing a position. 

Access Control
''''''''''''''
There is a access control for frontend editing based on user groups. Administrators can grant access for creating, editing and deleting organizations and positions in frontend. Admin user are allowed to perform all of these tasks.

Clients
'''''''
A service provider can grant access to its clients. Each client is allowed to edit its own organizations and positions. Display of records in frontend can be restricted to show only records of the currently logged in client. 

Outlook
=======
Future releases of these extension will include messaging about new jobs and applications, match making and editable user profiles.

