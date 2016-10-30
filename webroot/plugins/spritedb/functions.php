<?php

function getSprite($id)
{
	return Fetch(Query("
		SELECT 
			s.*, sr.locked
		FROM 
			{spriterevisions} sr 
			LEFT JOIN {sprites} s ON s.id=sr.id AND s.revision=sr.revision
		WHERE 
			sr.id={0}", $id));
}

$fieldtypes = array('checkbox', 'value',  'signedvalue', 'list', 'binary', 'index');

function trim_value(&$value) 
{ 
	$value = trim($value); 
}


function myisint($int)
{
    // First check if it's a numeric value as either a string or number
    if(is_numeric($int) === TRUE){
       
        // It's a number, but it has to be an integer
        if((int)$int == $int){

            return TRUE;
           
        // It's a number, but not an integer, so we fail
        }else{
       
            return FALSE;
        }
   
    // Not a number
    }else{
   
        return FALSE;
    }
}


//0: Type
//1: Nibbles
//2: Value
//3: Name
//4: Notes

function describefield($field, $html = true)
{
	$res = "";
	if($html)
		$res.= "<b>".htmlspecialchars($field[3])."</b>: ";
	else
		$res.= htmlspecialchars($field[3]).": ";

	$atnybble = "at nybble ".htmlspecialchars($field[1]);
	switch ($field[0])
	{
		case 'checkbox':
			$res .= "checkbox $atnybble with mask ".htmlspecialchars($field[2]);
			break;
		case 'value':
			$res .= "value $atnybble";
			break;
		case 'signedvalue':
			$res .= "signed value $atnybble";
			break;
		case 'index':
			$res .= "index at $atnybble";
			break;
		case 'binary':
			$res .= "binary editor $atnybble";
			break;
		case 'list':
			$listentries = str_replace("\n", ', ', rtrim($field[2]));
			$res .= "list $atnybble: ".htmlspecialchars($listentries)."";
		break;
	}

	if ($field[4] != '') $res.= ". ".htmlspecialchars($field[4])."";
	
	return $res;
}
