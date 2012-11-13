<?php

//=============================================================================
// Standard CMS Relationships
//=============================================================================

// upload.owner belongs_to $this->get('tableName')
dbx_addRelationship(array(
  'tableName'             => 'uploads',
  'relationshipName'      => 'owner',
  'singular'              => TRUE,
  'relatedTableFromField' => 'tableName', // FROM FIELD!
  'relatedCondition'      => array( 'num' => 'recordNum' ),
));

// account.accesslist has_many _accesslist
dbx_addRelationship(array(
  'tableName'        => 'accounts',
  'relationshipName' => 'accesslist',
  'relatedTable'     => '_accesslist',
  'relatedCondition' => array( 'userNum' => 'num' ),
));

// accesslist.account belongs_to accounts
dbx_addRelationship(array(
  'tableName'        => '_accesslist',
  'relationshipName' => 'account',
  'singular'         => TRUE,
  'relatedTable'     => 'accounts',
  'relatedCondition' => array( 'num' => 'userNum' ),
));

// ============================================================================

dbx_addRel('albums.all_photos', 'photos', array( 'album' => 'num', 'owner' => 'owner' ));
dbx_addRel('albums.photos',     'photos', array( 'album' => 'num', 'owner' => 'owner', 'approved=' => '1' ));

dbx_addRel('photos.album',     'albums',     array( 'num' => 'album' ), 'SINGULAR');
dbx_addRel('photos.feed_item', 'feed_items', array( 'tableOrTag=' => 'photos', 'recordNum' => 'num' ), 'SINGULAR');

dbx_addRel('accounts_website.feed_items',       'feed_items',       array( 'author' => 'num' ));
dbx_addRel('accounts_website.notifications',    'notifications',    array( 'owner' => 'num' ));
dbx_addRel('accounts_website.private_messages', 'private_messages', array( 'author' => 'num' ));
dbx_addRel('accounts_website.private_messages', 'private_messages', array( 'recipient' => 'num' ));
dbx_addRel('accounts_website.private_messages', 'private_messages', array( 'recipient' => 'num' ));

?>
