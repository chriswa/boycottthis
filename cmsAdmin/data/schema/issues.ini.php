<?php /* This is a PHP data file */ if (!@$LOADSTRUCT) { die("This is not a program file."); }
return array (
  '_detailPage' => 'boycott-detail.php',
  '_disableAdd' => '0',
  '_disableErase' => '0',
  '_disableModify' => '0',
  '_disablePreview' => '0',
  '_disableView' => '1',
  '_filenameFields' => 'title',
  '_hideRecordsFromDisabledAccounts' => '0',
  '_indent' => '0',
  '_listPage' => '',
  '_maxRecords' => '',
  '_maxRecordsPerUser' => '',
  '_perPageDefault' => '25',
  '_previewPage' => '',
  '_requiredPlugins' => '',
  '_tableName' => 'issues',
  'listPageFields' => 'organization, title, pledge_count, hidden, resolved',
  'listPageOrder' => '',
  'listPageSearchFields' => '_all_',
  'menuHidden' => '0',
  'menuName' => 'Issues',
  'menuOrder' => '5',
  'menuType' => 'multi',
  'num' => array(
    'order' => 1,
    'type' => 'none',
    'label' => 'Record Number',
    'isSystemField' => '1',
  ),
  'createdDate' => array(
    'order' => 2,
    'type' => 'none',
    'label' => 'Created',
    'isSystemField' => '1',
  ),
  'createdByUserNum' => array(
    'order' => 3,
    'type' => 'none',
    'label' => 'Created By',
    'isSystemField' => '1',
  ),
  'updatedDate' => array(
    'order' => 4,
    'type' => 'none',
    'label' => 'Last Updated',
    'isSystemField' => '1',
  ),
  'updatedByUserNum' => array(
    'order' => 5,
    'type' => 'none',
    'label' => 'Last Updated By',
    'isSystemField' => '1',
  ),
  'hidden' => array(
    'order' => 6,
    'label' => 'Hidden',
    'type' => 'checkbox',
    'fieldPrefix' => '',
    'checkedByDefault' => '0',
    'description' => 'Do not show this Issue on website',
    'checkedValue' => 'Yes',
    'uncheckedValue' => 'No',
  ),
  'resolved' => array(
    'order' => 7,
    'label' => 'Resolved',
    'type' => 'checkbox',
    'fieldPrefix' => '',
    'checkedByDefault' => '0',
    'description' => 'This Issue is no longer being boycotted',
    'checkedValue' => 'Yes',
    'uncheckedValue' => 'No',
  ),
  'date_posted' => array(
    'order' => '8',
    'label' => 'Date Posted',
    'type' => 'date',
    'fieldPrefix' => '',
    'description' => '',
    'isRequired' => '0',
    'isUnique' => '0',
    'defaultDate' => '',
    'defaultDateString' => '2012-01-01 00:00:00',
    'showTime' => '0',
    'showSeconds' => '0',
    'use24HourFormat' => '0',
    'yearRangeStart' => '',
    'yearRangeEnd' => '',
  ),
  'organization' => array(
    'order' => 9,
    'label' => 'Organization',
    'type' => 'textfield',
    'defaultValue' => '',
    'fieldPrefix' => '',
    'description' => '',
    'fieldWidth' => '',
    'isPasswordField' => '0',
    'isRequired' => '0',
    'isUnique' => '0',
    'minLength' => '',
    'maxLength' => '',
    'charsetRule' => '',
    'charset' => '',
  ),
  'title' => array(
    'order' => 10,
    'label' => 'Title',
    'type' => 'textfield',
    'defaultValue' => '',
    'description' => '',
    'fieldWidth' => '',
    'isPasswordField' => '0',
    'isRequired' => '1',
    'isUnique' => '0',
    'minLength' => '',
    'maxLength' => '',
    'charsetRule' => '',
    'charset' => '',
  ),
  'categories' => array(
    'order' => 11,
    'label' => 'Categories',
    'type' => 'list',
    'defaultValue' => '',
    'fieldPrefix' => '',
    'description' => '',
    'isRequired' => '0',
    'isUnique' => '0',
    'listType' => 'pulldownMulti',
    'optionsType' => 'table',
    'optionsTablename' => 'categories',
    'optionsValueField' => 'num',
    'optionsLabelField' => 'title',
  ),
  'summary' => array(
    'order' => 12,
    'label' => 'Summary',
    'type' => 'textbox',
    'defaultContent' => '',
    'fieldPrefix' => '',
    'description' => '',
    'isRequired' => '0',
    'isUnique' => '0',
    'minLength' => '',
    'maxLength' => '',
    'fieldHeight' => '',
    'autoFormat' => '1',
  ),
  'content' => array(
    'order' => 13,
    'label' => 'Content',
    'type' => 'wysiwyg',
    'defaultContent' => '',
    'allowUploads' => '1',
    'isRequired' => '0',
    'isUnique' => '0',
    'minLength' => '',
    'maxLength' => '',
    'fieldHeight' => '300',
    'allowedExtensions' => 'gif,jpg,png,wmv,mov,swf',
    'checkMaxUploadSize' => '1',
    'maxUploadSizeKB' => '5120',
    'checkMaxUploads' => '1',
    'maxUploads' => '25',
    'resizeOversizedImages' => '1',
    'maxImageHeight' => '800',
    'maxImageWidth' => '600',
    'createThumbnails' => '1',
    'maxThumbnailHeight' => '150',
    'maxThumbnailWidth' => '150',
    'useCustomUploadDir' => '0',
    'customUploadDir' => '',
    'customUploadUrl' => '',
  ),
  'links' => array(
    'order' => 14,
    'label' => 'Links',
    'type' => 'textbox',
    'defaultContent' => '',
    'fieldPrefix' => '',
    'description' => '',
    'isRequired' => '0',
    'isUnique' => '0',
    'minLength' => '',
    'maxLength' => '',
    'fieldHeight' => '',
    'autoFormat' => '1',
  ),
  'pledge_count' => array(
    'order' => 15,
    'label' => 'Pledge Count',
    'type' => 'textfield',
    'defaultValue' => '0',
    'fieldPrefix' => '',
    'description' => '',
    'fieldWidth' => '',
    'isPasswordField' => '0',
    'isRequired' => '0',
    'isUnique' => '0',
    'minLength' => '',
    'maxLength' => '',
    'charsetRule' => '',
    'charset' => '',
  ),
  'date_resolved' => array(
    'order' => '16',
    'label' => 'Date Resolved',
    'type' => 'date',
    'fieldPrefix' => '',
    'description' => '',
    'isRequired' => '0',
    'isUnique' => '0',
    'defaultDate' => 'none',
    'defaultDateString' => '2012-01-01 00:00:00',
    'showTime' => '0',
    'showSeconds' => '0',
    'use24HourFormat' => '0',
    'yearRangeStart' => '',
    'yearRangeEnd' => '',
  ),
  '__relatedRecords001__' => array(
    'order' => 17,
    'label' => 'Updates',
    'type' => 'relatedRecords',
    'relatedTable' => 'updates',
    'relatedLimit' => '',
    'relatedView' => '',
    'relatedModify' => '1',
    'relatedErase' => '1',
    'relatedCreate' => '1',
    'relatedWhere' => 'issue=\'<?php echo mysql_escape(@$RECORD[\'num\']) ?>\'',
    'relatedMoreLink' => 'issue_match=<?php echo htmlspecialchars(@$RECORD[\'num\']) ?>',
  ),
);
?>