.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Reference
^^^^^^^^^

This chapter describes the settings which are available in placements.
Except of setting the template paths and overriding labels of the
locallang-file, the settings are defined by using
plugin.tx\_placements.settings.<property>.

A simple way to get to know the default settings is to look at the
file EXT:placements/Configuration/TypoScript/setup.txt


General properties
""""""""""""""""""

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Data type:
         Data type:

   :Description:
         Description:

   :Default:
         Default:


 - :Property:
         view.templateRootPath

   :Data type:
         string

   :Description:
         Path to the templates. The default setting is
         EXT:placements/Resources/Private/Templates/

   :Default:
         **Extbase default**


 - :Property:
         view.partialRootPath

   :Data type:
         string

   :Description:
         Path to the partials. The default setting is
         EXT:placements/Resources/Private/Templates/

   :Default:
         **Extbase default**


 - :Property:
         view.layoutRootPath

   :Data type:
         string

   :Description:
         Path to the layouts. The default setting is
         EXT:placements/Resources/Private/Templates/

   :Default:
         **Extbase default**


 - :Property:
         maxFileSize

   :Data type:
         string

   :Description:
         The maximum file size allowed for uploads in frontend. 
         This value may be overriden by your PHP or TYPO3 settings.

   :Default:
         2 MB


 - :Property:
         listPid

   :Data type:
         integer

   :Description:
         Default page id for list views. This value can be overriden by any plugin.

   :Default:
         (empty)


 - :Property:
         detailPid

   :Data type:
         integer

   :Description:
         Default page id for detail views. This value can be overriden by any plugin.

   :Default:
         (empty)


 - :Property:
         orderBy

   :Data type:
         string

   :Description:
         Field which is used to sort placements records

   :Default:
         (empty)


 - :Property:
         orderDirection

   :Data type:
         string

   :Description:
         Field which is used to set sorting direction. This can either be desc
         or asc.

   :Default:
         desc


[tsref:plugin.tx\_placements.settings]


Security settings
"""""""""""""""""""""""

The following table describes the settings concerning the security settings. They are only important if using the frontend editing features. Currently positions and organizations can be created, edited and deleted in frontend.

**Important:** Those are set by using plugin.tx\_placements.settings.security.<Record Type>.


.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Data type:
         Data type:

   :Description:
         Description:

   :Default:
         Default:


 - :Property:
         editorGroup

   :Data type:
         string

   :Description:
         A comma separated list of frontend user group uids. Member of this groups are allowed to edit records (i.e. positions or organizations) in frontend.

   :Default:
        (empty)

 - :Property:
         creatorGroup

   :Data type:
         string

   :Description:
        A comma separated list of frontend user group uids. Members of this groups are allowed to create records in frontend.

   :Default:
         (empty)


 - :Property:
         deleteGroup

   :Data type:
         string

   :Description:
        A comma separated list of frontend user group ids. Members of this groups are allowed to delete records in frontend.

   :Default:
         (empty)

 - :Property:
         adminGroup

   :Data type:
         string

   :Description:
         A comma separated list of frontend user group ids. Members of this groups are allowed to perform all actions in frontend (create, edit, delete)

   :Default:
         (empty)

Plugin position
""""""""""""""""""""""""""""""""""

The following table describes the settings concerning positions.

1. List view

**Important:** Those are set by using plugin.tx\_placements.settings.position.list

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Data type:
         Data type:

   :Description:
         Description:

   :Default:
         Default:


 - :Property:
         summary.crop

   :Data type:
         integer

   :Description:
         Number of letters for cropping the content of the summary field in list view.

   :Default:
         35

 - :Property:
         entryDate.format

   :Data type:
         string

   :Description:
         Format of the field entryDate in list view. Use a string accepted by \<f.format.date\> viewhelper    

   :Default:
          \d. F Y

2. Detail View

**Important:** Those are set by using plugin.tx\_placements.settings.position.detail

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Data type:
         Data type:

   :Description:
         Description:

   :Default:
         Default:

 - :Property:
         errorHandling

   :Data type:
         string

   :Description:
         If the requested entry is not found, it is possible to use various types of error handling.
         
         - **redirectToListView**: This will redirect to the list view on the same page.
         - **redirectToPage**: Redirect to any page by using the syntax redirectToPage,<pageid>,<status>. This means e.g. redirectToPage,123,404 to redirect to the page with UID 123 and error code 404.
         - **pageNotFoundHandler**: The default page not found handler will be called.
         If not set, the current TYPO3 error handling is used.
   :Default:
        (empty)

