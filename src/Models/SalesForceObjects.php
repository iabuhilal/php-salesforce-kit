<?php
namespace iabuhilal\Salesforce\Exception;

class ActionOverrides {
    public $formFactor; //String
    public $isAvailableInTouch; //boolean
    public $name; //String
    public $pageId; //String
    public $url; //array( undefined )

}
class ChildRelationships {
    public $cascadeDelete; //boolean
    public $childSObject; //String
    public $deprecatedAndHidden; //boolean
    public $Fields;
    public $junctionIdListNames;  //array( undefined )
    public $junctionReferenceTo;  //array( undefined )
    public $relationshipName; //array( undefined )
    public $restrictedDelete; //boolean

}
class Fields {
    public $aggregatable; //boolean
    public $aiPredictionField; //boolean
    public $autoNumber; //boolean
    public $byteLength; //int
    public $calculated; //boolean
    public $calculatedFormula; //array( undefined )
    public $cascadeDelete; //boolean
    public $caseSensitive; //boolean
    public $compoundFieldName; //array( undefined )
    public $controllerName; //array( undefined )
    public $createable; //boolean
    public $custom; //boolean
    public $defaultValue; //array( undefined )
    public $defaultValueFormula; //array( undefined )
    public $defaultedOnCreate; //boolean
    public $dependentPicklist; //boolean
    public $deprecatedAndHidden; //boolean
    public $digits; //int
    public $displayLocationInDecimal; //boolean
    public $encrypted; //boolean
    public $externalId; //boolean
    public $extraTypeInfo; //array( undefined )
    public $filterable; //boolean
    public $filteredLookupInfo; //array( undefined )
    public $formulaTreatNullNumberAsZero; //boolean
    public $groupable; //boolean
    public $highScaleNumber; //boolean
    public $htmlFormatted; //boolean
    public $idLookup; //boolean
    public $inlineHelpText; //array( undefined )
    public $label; //String
    public $length; //int
    public $mask; //array( undefined )
    public $maskType; //array( undefined )
    public $name; //String
    public $nameField; //boolean
    public $namePointing; //boolean
    public $nillable; //boolean
    public $permissionable; //boolean
    public $picklistValues;  //array( PicklistValues  )
    public $polymorphicForeignKey; //boolean
    public $precision; //int
    public $queryByDistance; //boolean
    public $referenceTargetField; //array( undefined )
    public $referenceTo;  //array( undefined )
    public $relationshipName; //array( undefined )
    public $relationshipOrder; //array( undefined )
    public $restrictedDelete; //boolean
    public $restrictedPicklist; //boolean
    public $scale; //int
    public $searchPrefilterable; //boolean
    public $soapType; //String
    public $sortable; //boolean
    public $type; //String
    public $unique; //boolean
    public $updateable; //boolean
    public $writeRequiresMasterRead; //boolean

}

class PicklistValues {
    public $active; //boolean
    public $defaultValue; //boolean
    public $label; //String
    public $validFor; //array( undefined )
    public $value; //String

}

class RecordTypeInfos {
    public $active; //boolean
    public $available; //boolean
    public $defaultRecordTypeMapping; //boolean
    public $developerName; //String
    public $master; //boolean
    public $name; //String
    public $recordTypeId; //String
    public $urls; //Urls

}
class SupportedScopes {
    public $label; //String
    public $name; //String

}
class Urls {
    public $compactLayouts; //String
    public $rowTemplate; //String
    public $approvalLayouts; //String
    public $uiDetailTemplate; //String
    public $uiEditTemplate; //String
    public $defaultValues; //String
    public $listviews; //String
    public $describe; //String
    public $uiNewRecord; //String
    public $quickActions; //String
    public $layouts; //String
    public $sobject; //String

}
class Application {
    public $actionOverrides; //array( ActionOverrides )
    public $activateable; //boolean
    public $childRelationships; //array( ChildRelationships )
    public $compactLayoutable; //boolean
    public $createable; //boolean
    public $custom; //boolean
    public $customSetting; //boolean
    public $deletable; //boolean
    public $deprecatedAndHidden; //boolean
    public $feedEnabled; //boolean
    public $fields; //array( Fields )
    public $hasSubtypes; //boolean
    public $isSubtype; //boolean
    public $keyPrefix; //String
    public $label; //String
    public $labelPlural; //String
    public $layoutable; //boolean
    public $listviewable; //array( object )
    public $lookupLayoutable; //array( object )
    public $mergeable; //boolean
    public $mruEnabled; //boolean
    public $name; //String
    public $namedLayoutInfos;  //array( undefined )
    public $networkScopeFieldName; //array( undefined )
    public $queryable; //boolean
    public $recordTypeInfos; //array( RecordTypeInfos )
    public $replicateable; //boolean
    public $retrieveable; //boolean
    public $searchLayoutable; //boolean
    public $searchable; //boolean
    public $sobjectDescribeOption; //String
    public $supportedScopes; //array( SupportedScopes )
    public $triggerable; //boolean
    public $undeletable; //boolean
    public $updateable; //boolean
    public $urls; //Urls

}