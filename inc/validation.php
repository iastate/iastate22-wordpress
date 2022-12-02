<?php

/*---===/ Sets Input Fields based on Object selection \===---*/
function my_acf_input_admin_footer() {
	// Only load the script on block editor pages.
	$screen = get_current_screen();
	if ( ! $screen->is_block_editor() ){
		return;
	}
?>
<script type="text/javascript">
(function($) {
    // Copied from forum thread about bug.
    $(window).bind("load", function () {
        let form = $('.editor-styles-wrapper .is-root-container'),
            wrapper = $('.block-editor-block-list__layout'),
            header = document.querySelector('.edit-post-header__settings'),
            editBlock,
            validatorMsg = document.createElement("div");
        
        validatorMsg.classList.add('validator-message');
        header.appendChild(validatorMsg);
            
        $('button.editor-post-publish-button.is-primary').attr('disabled', 'disabled');
        
        setTimeout(function() {
            editBlock = form.find('.wp-block');
            editBlock.each(function(e) {
                let ths = this;
                var observer = new MutationObserver(function(mutations) {
                    checkRequired(form);   
                });

                // Pass in the target node, as well as the observer options.
                observer.observe(ths, {
                    attributes:    true,
                    childList:     true,
                    characterData: true
                });
            });
            checkRequired(form);
            $(wrapper).on('DOMSubtreeModified', function(e) {
                checkRequired(form);
            })
        }, 3000);
    });
    
    function checkRequired(form) {
        let inputs = $(form).find('input, textarea, select'),
            requiredFields = [],
            vM = document.querySelector('.edit-post-header__settings .validator-message'),
            tally = 0;
        for(var i = 0; i < inputs.length; i++) {
            if(inputs[i].getAttribute('required') !== null && inputs[i].getAttribute('required') !== false) {
                requiredFields.push(inputs[i]);
            }
            $(inputs[i]).off('input');
            $(inputs[i]).on('input', function(e) {
                liveValidate(e.currentTarget, form);
            });
        }

        $(requiredFields).each(function(e) {
            let valid = this.checkValidity();
            let rt = this.parentNode.parentNode;
            let type = this.getAttribute("type");
            let errorMessage;
            
            if(rt.querySelector(".error-message") === null) {
                errorMessage = document.createElement("div");
                errorMessage.classList.add("error-message");
                rt.appendChild(errorMessage);
            }

            if(valid === false) {
                tally++;
                console.log(type);
                if(this.classList.contains("validation-error") === false) {
                    this.classList.add("validation-error");
                    let msg = "";
                    if(type === "url") {
                        msg = "Field must start with https://"
                    } else {
                        msg = "Field is required"
                    }
                    rt.querySelector('.error-message').textContent = ""+msg+"";
                    rt.classList.add("invalid");
                }
            } else {
                this.classList.remove("validation-error");
                rt.classList.remove("invalid");
            }
        });
        if(tally > 0) {
            console.log("Validation Failures: "+tally);
            
            if(tally > 1) {
                vM.textContent = ""+tally+" fields require attention"
            } else {
                vM.textContent = ""+tally+" field requires attention"
            }
            $('button.editor-post-publish-button.is-primary').attr('disabled', 'disabled');
        } else {
            $('button.editor-post-publish-button.is-primary').removeAttr('disabled');
            vM.textContent = "";
        }
    }
    
    function liveValidate(field, form) {
        let valid = field.checkValidity();
        let rt = field.parentNode.parentNode;

        if(valid === false) {
            if(field.classList.contains("validation-error") === false) {
                checkRequired(form);
                field.classList.add("validation-error");
                rt.classList.add("invalid");
            }
        } else {
            field.classList.remove("validation-error");
            rt.classList.remove("invalid");
            checkRequired(form);
        }
    }

})(jQuery);	
</script>
<?php         
}
add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');