tinyMCEPopup.requireLangPack();

var StyledtextDialog = {
	init : function() {
		var f = document.forms[0];
                var inst = tinyMCEPopup.editor, dom = inst.dom;
                var elm = dom.get(inst.selection.getNode());
                 console.log(elm);
		// Get the selected contents as text and place it in the input
		f.title.value = tinyMCEPopup.editor.selection.getContent();
		f.content.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
	},

	insert : function() {
		// Insert the contents from the input into the document
                var formObj = document.forms[0];
                var inst = tinyMCEPopup.editor, dom = inst.dom;
                var html = '';

                // Create new table
                html += '                   <div class="textBox"> ';
                html += '                       <div class="hd"> ';
                html += '                           <div class="c">&nbsp;';
                html += '                        </div>';
                html += '                       </div>';
                html += '                    <div class="bd"> ';
                html += '                        <div class="c"> ';
                html += '                           <div class="s"> ';
                html += '                                <div class="content"> ';
                html += '                                   <div class="contentLeft"> ';
                html += '                                      ' + formObj.title.value;
                html += '                                    </div> ';
                html += '                                    <div class="contentRight"> ';
                html += '                                      ' + formObj.content.value;
                html += '                                    </div>';
                html += '                                </div>';
                html += '                            </div>';
                html += '                        </div>';
                html += '                    </div>'
                html += '                    <div class="ft">';
                html += '                       <div class="c">&nbsp;</div>';
                html += '                    </div>';
                html += '                </div>';
                html += '                <p></p>';

                inst.execCommand('mceBeginUndoLevel');
                inst.execCommand('mceInsertContent', false, html);
                inst.addVisual();
                inst.execCommand('mceEndUndoLevel');
//		tinyMCEPopup.editor.execCommand('mceInsertContent', false, document.forms[0].someval.value);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(StyledtextDialog.init, StyledtextDialog);
