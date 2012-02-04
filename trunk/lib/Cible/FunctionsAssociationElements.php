<?php
abstract class Cible_FunctionsAssociationElements
{
    /**
     * Render a new element in the form to display elements
     * to associate with the new record.
     *
     * @param string $associationSetID    Define the element type used for
     *                                    assocaition.
     *                                    It's needed to create element set.
     * @param string  $fieldPrefix        Prefix used in the table to define
     *                                    the good offset in the array.
     * @param string $dataFieldToDisplay  The field name containing data to
     *                                    display.
     * @param int    $associationSetCpt   ??
     * @param string $associationSetTitle Title which will be displayed.
     * @param string $associationsData    Data from element table to associate
     *                                    with.
     * @param array  $associationArray    ??
     *
     * @return string
     */
    public static function getNewAssociationSetBox(
        $associationSetID,
        $fieldPrefix,
        $dataFieldToDisplay,
        $associationSetCpt,
        $associationSetTitle,
        $associationsData,
        $associationArray = array())
    {
        $idOffset = $fieldPrefix . 'ID';

        if(count($associationArray) == 0)
        {
//            $associationArray[0] = -1;
        }
        else{
            $associationArrayTmp = array();
            foreach($associationArray as $association){
                $associationArrayTmp[] = $association;
            }

            $associationArray = $associationArrayTmp;
        }

        $cptAssociation = count($associationArray);

        $newSetBox = '';
        $newSetBox .= '<fieldset id="fieldset-'.$associationSetID.'">';
        $newSetBox .= ' <div id="associationSet_'.$associationSetCpt.'" class="associationSetContent" associationSetID="'.$associationSetID.'">';
        $newSetBox .= "     <div class='associationSetContent_action'>";
        $newSetBox .= "         <div class='action'>";
        $newSetBox .= "             <fieldset id='fieldset-actions-association'>";
        $newSetBox .= "                 <ul class='actions-buttons'>";
        $newSetBox .= "                     <li><button name='addAssociation' id='addAssociation' type='button' class='stdButton addAssociation'>Ajouter</button></li>";
        $newSetBox .= "                 </ul>";
        $newSetBox .= "             </fieldset>";
        $newSetBox .= "         </div>";
        $newSetBox .= "         <div class='title'>".$associationSetTitle."</div>";
        $newSetBox .= "     </div>";
        $newSetBox .= '     <div class="associationContent">';
        $newSetBox .= '         <input type="hidden" id="associationCountID" value="'.$cptAssociation.'"/>';
        $newSetBox .= '         <input type="hidden" id="associationCount" value="'.$cptAssociation.'"/>';
        $newSetBox .= '         <table cellpadding="0" cellspacing="0">';

        for($i=0;$i<$cptAssociation;$i++){

            if (($i)%2)
                $row = 'row_odd';
            else
                $row = 'row_even';

            $newSetBox .= '             <tr class="association" associationID="'.($i).'">';
            $newSetBox .= '                 <td class="tdSelectAssociationOption '.$row.'">';
            if (count($associationArray) > 0)
            {
            $newSetBox .= '                     <select name="'.$associationSetID.'Set['.($i).']" id="'.$associationSetID.'Set" class="selectAssociationOption">';
            $newSetBox .= "                         <option value='-1'>"
                                                    . Cible_Translation::getCibleText("association_set_selectOne")
                                                    . "-</option>";

            foreach($associationsData as $association)
            {
                $newSetBox .= "                     <option value='".$association[$idOffset]."'";
                if($association[$idOffset] == $associationArray[$i])
                    $newSetBox .= " selected='selected'";

                $newSetBox .= ">".$association[$dataFieldToDisplay]."</option>";
            }
            $newSetBox .= "                     </select>";
            }
            $newSetBox .= "                 </td>";
            $newSetBox .= '                 <td class="tdAssociationAction '.$row.'">';
            if (count($associationArray) > 0)
            {
            $newSetBox .= "                     <div class='action'>";
            $newSetBox .= "                         <fieldset id='fieldset-actions-association'>";
            $newSetBox .= "                             <ul class='actions-buttons'>";
            $newSetBox .= "                                 <li><button name='deleteAssociation' id='deleteAssociation' type='button' class='stdButton delAssociation'>Supprimer</button></li>";
            $newSetBox .= "                             </ul>";
            $newSetBox .= "                         </fieldset>";
            $newSetBox .= "                     </div>";
            }
            $newSetBox .= "                 </td>";
            $newSetBox .= "             </tr>";

        }
        $newSetBox .= "         </table>";

        $newSetBox .= "     </div>";


        $newSetBox .= " </div>";
        $newSetBox .= "</fieldset>";


        return $newSetBox;
    }

    public static function getNewAssociationSeqSetBox(
        $associationSetID,
        $fieldPrefix,
        $dataFieldToDisplay,
        $associationSetCpt,
        $associationSetTitle,
        $associationsData,
        $associationArray = array())
    {
        $idOffset = $fieldPrefix . 'ID';
        if(count($associationArray) == 0){
            $associationArray[0][$idOffset] = -1;
            $associationArray[0]['seq'] = '';

        }
        else{
            $associationArrayTmp = array();
            foreach($associationArray as $association){
                $associationArrayTmp[] = $association;
            }

            $associationArray = $associationArrayTmp;
        }

        $cptAssociation = count($associationArray);

        $newSetBox = '';
        $newSetBox .= '<fieldset id="fieldset-'.$associationSetID.'">';
        $newSetBox .= ' <div id="associationSet_'.$associationSetCpt.'" class="associationSetContent" associationSetID="'.$associationSetID.'">';
        $newSetBox .= "     <div class='associationSetContent_action'>";
        $newSetBox .= "         <div class='action'>";
        $newSetBox .= "             <fieldset id='fieldset-actions-association'>";
        $newSetBox .= "                 <ul class='actions-buttons'>";
        $newSetBox .= "                     <li><button name='addAssociationSeq' id='addAssociationSeq' type='button' class='stdButton addAssociation'>Ajouter</button></li>";
        $newSetBox .= "                 </ul>";
        $newSetBox .= "             </fieldset>";
        $newSetBox .= "         </div>";
        $newSetBox .= "         <div class='title'>".$associationSetTitle."</div>";
        $newSetBox .= "     </div>";
        $newSetBox .= '     <div class="associationContent">';
        $newSetBox .= '         <input type="hidden" id="associationCountID" value="'.$cptAssociation.'"/>';
        $newSetBox .= '         <input type="hidden" id="associationCount" value="'.$cptAssociation.'"/>';
        $newSetBox .= '         <table cellpadding="0" cellspacing="0">';

        for($i=0;$i<$cptAssociation;$i++){

            if (($i)%2)
                $row = 'row_odd';
            else
                $row = 'row_even';

            $newSetBox .= '             <tr class="association '.$row.'" associationID="'.($i).'">';
            $newSetBox .= '                 <td class="tdSelectAssociationOption ">';
            $newSetBox .= '                     <select name="'.$associationSetID.'Set['.($i).'][ID]" id="'.$associationSetID.'Set" class="selectAssociationOption">';
            $newSetBox .= "                         <option value='-1'>"
                    . Cible_Translation::getCibleText("association_set_selectOne")
                    . "</option>";

            foreach($associationsData as $association){
                $newSetBox .= "                     <option value='".$association[$idOffset]."'";
                if($association[$idOffset] == $associationArray[$i][$idOffset])
                    $newSetBox .= " selected='selected'";

                $newSetBox .= ">".$association[$dataFieldToDisplay]."</option>";
            }
            $newSetBox .= "                     </select>";
            $newSetBox .= "                 </td>";
            $newSetBox .= "                 <td >&nbsp;</td>";
            $newSetBox .= "                 <td ><strong>Sequence : </strong><input type='text' name='{$associationSetID}Set[$i][seq]' class='shortTextInput $row' value='{$associationArray[$i]['seq']}'/></td>";
            $newSetBox .= '                 <td class="tdAssociationAction '.$row.'">';
            $newSetBox .= "                     <div class='action'>";
            $newSetBox .= "                         <fieldset id='fieldset-actions-association'>";
            $newSetBox .= "                             <ul class='actions-buttons'>";
            $newSetBox .= "                                 <li><button name='deleteAssociation' id='deleteAssociation' type='button' class='stdButton delAssociation'>Supprimer</button></li>";
            $newSetBox .= "                             </ul>";
            $newSetBox .= "                         </fieldset>";
            $newSetBox .= "                     </div>";
            $newSetBox .= "                 </td>";
            $newSetBox .= "             </tr>";

        }
        $newSetBox .= "         </table>";

        $newSetBox .= "     </div>";


        $newSetBox .= " </div>";
        $newSetBox .= "</fieldset>";


        return $newSetBox;
    }


      /**
     * Render a new element in the form to display elements
     * to associate with the new record.
     *
     * @param string $associationSetID    Define the element type used for
     *                                    assocaition.
     *                                    It's needed to create element set.
     * @param string  $fieldPrefix        Prefix used in the table to define
     *                                    the good offset in the array.
     * @param string $dataFieldToDisplay  The field name containing data to
     *                                    display.
     * @param int    $associationSetCpt   ??
     * @param string $associationSetTitle Title which will be displayed.
     * @param string $associationsData    Data from element table to associate
     *                                    with.
     * @param array  $associationArray    ??
     *
     * @return string
     */
    public static function getNewAssociationSetInput(
        $associationSetID,
        $fieldPrefix,
        $dataFieldToDisplay,
        $associationSetCpt,
        $associationSetTitle,
        $associationsData,
        $associationArray = array())
    {
        $idOffset = $fieldPrefix . 'ID';

        if(count($associationArray) == 0){
            $associationArray[0] = -1;
        }
        else{
            $associationArrayTmp = array();
            foreach($associationArray as $association){
                $associationArrayTmp[] = $association;
            }

            $associationArray = $associationArrayTmp;
        }

        $cptAssociation = count($associationArray);

        $newSetBox = '';
        $newSetBox .= '<fieldset id="fieldset-'.$associationSetID.'">';
        $newSetBox .= ' <div id="associationSet_'.$associationSetCpt.'" class="associationSetContent" associationSetID="'.$associationSetID.'">';
        $newSetBox .= "     <div class='associationSetContent_action'>";
        $newSetBox .= "         <div class='action'>";
        $newSetBox .= "             <fieldset id='fieldset-actions-association'>";
        $newSetBox .= "                 <ul class='actions-buttons'>";
        $newSetBox .= "                     <li><button name='addAssociation' id='addAssociation' type='button' class='stdButton addAssociation'>Ajouter</button></li>";
        $newSetBox .= "                 </ul>";
        $newSetBox .= "             </fieldset>";
        $newSetBox .= "         </div>";
        $newSetBox .= "         <div class='title'>".$associationSetTitle."</div>";
        $newSetBox .= "     </div>";
        $newSetBox .= '     <div class="associationContent">';
        $newSetBox .= '         <input type="hidden" id="associationCountID" value="'.$cptAssociation.'"/>';
        $newSetBox .= '         <input type="hidden" id="associationCount" value="'.$cptAssociation.'"/>';
        $newSetBox .= '         <table cellpadding="0" cellspacing="0">';

        for($i=0;$i<$cptAssociation;$i++){

            if (($i)%2)
                $row = 'row_odd';
            else
                $row = 'row_even';

            $newSetBox .= '             <tr class="'.$row.' association" associationID="' . ($i) . '">';
            $newSetBox .= '                 <td id="Order_'. $i .'" class="dragZone ">' . chr(13);
            $newSetBox .= '                     ' . chr(13);
            $newSetBox .= '                     <input name="'.$associationSetID.'Set['.($i).']" class="dragZone " type="hidden" value="' . $associationArray[$i]['order'] . '" />' . chr(13);
            $newSetBox .= '                 </td>' . chr(13);
            $newSetBox .= '                 <td class="tdSelectAssociationOption ">';
            $newSetBox .= '                     <input name="'.$associationSetID.'Set['.($i).']" id="'.$associationSetID.'Set" class="selectAssociationOption" value="'.$associationArray[$i]['value'].'"/>';
            $newSetBox .= "                 </td>";
            $newSetBox .= '                 <td class="tdAssociationAction">';
            $newSetBox .= "                     <div class='action'>";
            $newSetBox .= "                         <fieldset id='fieldset-actions-association'>";
            $newSetBox .= "                             <ul class='actions-buttons'>";
            $newSetBox .= "                                 <li><button name='deleteAssociation' id='deleteAssociation' type='button' class='stdButton delAssociation'>Supprimer</button></li>";
            $newSetBox .= "                             </ul>";
            $newSetBox .= "                         </fieldset>";
            $newSetBox .= "                     </div>";
            $newSetBox .= "                 </td>";
            $newSetBox .= "             </tr>";

        }
        $newSetBox .= "         </table>";

        $newSetBox .= "     </div>";


        $newSetBox .= " </div>";
        $newSetBox .= "</fieldset>";


        return $newSetBox;
    }

    public static function getNewAssociationSetBoxSelect(
        $associationSetID,
        $fieldPrefix,
        $dataFieldToDisplay,
        $associationSetData,
        $associationSetCpt,
        $associationSetTitle,
        $associationsData,
        $associationArray = array(),
        $errorsMessage = array())
    {
        $idOffset = $fieldPrefix . 'ID';
        if(count($associationArray) == 0)
        {
//            $associationArray[0] = -1;
}
        else{
            $associationArrayTmp = array();
            foreach($associationArray as $association){
                $associationArrayTmp[] = $association;
            }

            $associationArray = $associationArrayTmp;
        }

        $cptAssociation = count($associationArray);

        $newSetBox = '';
        $newSetBox .= '<fieldset id="fieldset-'.$associationSetID.'">';
        $newSetBox .= ' <div id="associationSet_'.$associationSetCpt.'" class="associationSetContent" associationSetID="'.$associationSetID.'">';
        $newSetBox .= "     <div class='associationSetContent_action'>";
        $newSetBox .= "         <div class='action'>";
        $newSetBox .= "             <fieldset id='fieldset-actions-association'>";
        $newSetBox .= "                 <ul class='actions-buttons'>";
        $newSetBox .= "                     <li><button name='addAssociation' id='addAssociation' type='button' class='stdButton addAssociation'>Ajouter</button></li>";
        $newSetBox .= "                 </ul>";
        $newSetBox .= "             </fieldset>";
        $newSetBox .= "         </div>";
        $newSetBox .= "         <div class='title'>".$associationSetTitle."</div>";
        $newSetBox .= "     </div>";
        $newSetBox .= '     <div class="associationContent">';
        $newSetBox .= '         <input type="hidden" id="associationCountID" value="'.$cptAssociation.'"/>';
        $newSetBox .= '         <input type="hidden" id="associationCount" value="'.$cptAssociation.'"/>';
        $newSetBox .= '         <table cellpadding="0" cellspacing="0">';

        for($i=0;$i<$cptAssociation;$i++){

            if (($i)%2)
                $row = 'row_odd';
            else
                $row = 'row_even';

            $newSetBox .= '             <tr class="association '.$row.'" associationID="'.($i).'">';
            $newSetBox .= '                 <td class="tdSelectAssociationOption '.$row.'">';
            if (count($associationArray) > 0)
            {
                $newSetBox .= '                    <select name="'.$associationSetID.'Set['.($i).'][colorID]" id="'.$associationSetID.'Set" class="selectMenu selectAssociationOption">';
                $newSetBox .= "                         <option value='-1'>"
                                                        . Cible_Translation::getCibleText("association_set_selectOne")
                                                        . "-</option>";
                $selected = '';
                foreach($associationsData as $association)
                {
                    $titleValue = '';
                    $class = '';
                    if (array_key_exists($associationSetData, $association))
                    {
                        $titleValue = $association[$associationSetData];
                        $class = 'color';
                    }
                    $newSetBox .= "                     <option value='".$association[$idOffset]."'";
                    if((isset($associationArray[$i]['value']) && $association[$idOffset] == $associationArray[$i]['value']) || ($association[$idOffset] == $associationArray[$i]) || (isset($associationArray[$i]['colorID']) && $association[$idOffset] == $associationArray[$i]['colorID']))
                    {
                        $newSetBox .= " selected='selected'";
                        $selected = $association[$idOffset];
                    }
                    $newSetBox .= " title='". $titleValue ."' class='". $class ."'>".$association[$dataFieldToDisplay]."</option>";

                }
                $newSetBox .= "                     </select> <span class='field_required'>*</span>";

                if (!empty($errorsMessage['emptyColorMsg']) && $selected < 1)
                {
                    $newSetBox .= '<ul class="errors">';
                    $newSetBox .= '<li>' . $errorsMessage['emptyColorMsg'] .'</li>';
                    $newSetBox .= '</ul>';
                }
            }
            $newSetBox .= "                 </td>";
            $newSetBox .= '                 <td class="tdSelectAssociationOption '.$row.'">';
            if (count($associationArray) > 0)
            {
                $newSetBox .= '                     <select name="'.$associationSetID.'Set['.($i).'][type]" id="'.$associationSetID.'SetType" class="selectAssociationOption">';
                $newSetBox .= "                         <option value='-1'>"
                                                        . Cible_Translation::getCibleText("association_set_selectOne")
                                                        . "-</option>";

                $hasSelected = false;
                $selectedFirst = '';
                $selectedSec = '';
                if ((isset($associationArray[$i]['CCP_Type']) && $associationArray[$i]['CCP_Type'] == 1)
                    || (isset($associationArray[$i]['type']) && $associationArray[$i]['type'] == 1))
                {
                    $selectedFirst = true;
                    $hasSelected = true;
                }
                elseif ((isset($associationArray[$i]['CCP_Type']) && $associationArray[$i]['CCP_Type'] == 2)
                    || (isset($associationArray[$i]['type']) && $associationArray[$i]['type'] == 2))
                {
                    $selectedSec = true;
                    $hasSelected = true;
                }

                $newSetBox .= "                         <option value='1'" . (($selectedFirst) ? 'selected="selected" ' : "") . ">"
                                                        . Cible_Translation::getCibleText("association_set_colorType_available")
                                                        . "</option>";
                $newSetBox .= "                         <option value='2'" . (($selectedSec) ? 'selected="selected" ' : "") . ">"
                                                        . Cible_Translation::getCibleText("association_set_colorType_order")
                                                        . "</option>";
                $newSetBox .= "                     </select> <span class='field_required'>*</span>";

                if (!empty($errorsMessage['emptyTypeMsg']) && !$hasSelected)
                {
                    $newSetBox .= '<ul class="errors">';
                    $newSetBox .= '<li>' . $errorsMessage['emptyTypeMsg'] .'</li>';
                    $newSetBox .= '</ul>';
                }
            }
            $newSetBox .= "                 </td>";
            $newSetBox .= '                 <td class="tdAssociationAction '.$row.'">';

            if (count($associationArray) > 0)
            {
                $newSetBox .= "                     <div class='action'>";
                $newSetBox .= "                         <fieldset id='fieldset-actions-association'>";
                $newSetBox .= "                             <ul class='actions-buttons'>";
                $newSetBox .= "                                 <li><button name='deleteAssociation' id='deleteAssociation' type='button' class='stdButton delAssociation'>Supprimer</button></li>";
                $newSetBox .= "                             </ul>";
                $newSetBox .= "                         </fieldset>";
                $newSetBox .= "                     </div>";
            }
            $newSetBox .= "                 </td>";
            $newSetBox .= "             </tr>";

        }
        $newSetBox .= "         </table>";

        $newSetBox .= "     </div>";


        $newSetBox .= " </div>";
        $newSetBox .= "</fieldset>";


        return $newSetBox;
    }

}