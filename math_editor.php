<?php

if (!defined('MEDIAWIKI')) {
    die("This file is part of MediaWiki and is not a valid entry point\n");
}
$myRedirectPng = "/extensions/mathEditor/myRedirect.png";

$wgHooks['EditPage::showEditForm:initial'][] = 'mathEditor';
$wgHooks['EditPageBeforeEditToolbar'][] = 'addMathButton';

$wgExtensionCredits['mathEditor'][] = array(
    'name' => 'my Redirect Button Extension',
    'version' => '1.0.0',
    'author' => 'Muhammad Al-Syrwan',
    'url' => 'http://github.com/wikitechie/mediawikiMathEditor',
    'description' => 'This extension adds a Latex editor using codecogs');

function getEditorHTML() {
    $html = <<< EDITOR
        <script type='text/javascript' src='http://latex.codecogs.com/js/eq_editor-lite-11.js' ></script>
	<div style='border:1px solid gray; clear:both;'>
	<div id="editor" style=""></div>
	<table style='width:100%'>
	<tr><td>
		<textarea id="mathtestbox" style = "resize:none;"></textarea>
	</td><td>
		==&gt
		<img style='padding-left:10px' id="equation" />
	</td></tr>
	</table>
	<script type="text/javascript">
	  EqEditor.embed('editor','things','mini','en-us')
	  var a=new EqTextArea('equation', 'mathtestbox');
	  EqEditor.add(a,false);
	</script>
	</div>

EDITOR;
    return $html;
}

function addMathButton(&$toolbar) {
    //global $myRedirectPng;
    $toolbar = str_replace('</div>', "<a onclick='writeMath()'>Insert Latex</a></div>", $toolbar);
    return true;
}

// Add a button to the internal editor
function mathEditor($editPage) {
    global $wgOut, $wgScriptPath, $myRedirectPng;

    $wgOut->addLink(array(
        'rel' => 'stylesheet',
        'type' => 'text/css',
        'href' => 'http://latex.codecogs.com/css/equation-embed.css'
    ));
    $editorHTML = getEditorHTML();
    $editPage->editFormTextTop .= $editorHTML;
    // Insert javascript script that hooks up to create button.
    $wgOut->addScript("<script type='text/javascript' src='http://latex.codecogs.com/js/eq_config.js' ></script>");
    $wgOut->addScript("<script type='text/javascript' >function getMathText() { var box = document.getElementById('mathtestbox'); return box.value;}</script>");
    $wgOut->addScript("<script type=\"text/javascript\">\n" .
            "function writeMath(){\n" .
            "  insertTags('<math>','</math>',getMathText());" .
            "  }\n" .
            "</script>");
    return true;
}

?>
