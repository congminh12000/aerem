<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcPlainClassesOverlay';
$prefix_id = 'brxcPlainClasses';
$prefix_class = 'brxc-plain-classes';
// Heading
$modal_heading_title = 'Plain Classes';

?>
<!-- Main -->
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onclick="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
    <!-- Main Inner -->
    <div class="brxc-overlay__inner brxc-medium" style="max-height: 870px;">
        <!-- Close Modal Button -->
        <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
            <i class="bricks-svg ti-close"></i>
        </div>
        <!-- Modal Wrapper -->
        <div class="brxc-overlay__inner-wrapper">
            <!-- Modal Header -->
            <div class="brxc-overlay__header">
                <!-- Modal Header Title-->
                <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>
                <div class="brxc-overlay__resize-icons">
                    <i class="fa-solid fa-window-maximize" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-left" onclick="ADMINBRXC.leftSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-right" onclick="ADMINBRXC.rightSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                </div>
            </div>
            <!-- Modal Error Container for OpenAI -->
            <div class="brxc-overlay__error-message-wrapper"></div>
            <!-- Modal Container -->
            <div class="brxc-overlay__container">
                <!-- Modal Panels Wrapper -->
                <div class="brxc-overlay__pannels-wrapper">
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                        <!-- Panel Content -->
                        <p class="brxc-overlay-css__desc" data-control="info">Update the classes in bulk. Seperate each different class by a space, without dot. Any deleted class from the list will be removed too.</p>
                        <textarea name="plain-classes" id="plainClassesInput" placeholder="Type your classes here..." cols="30" rows="10"></textarea>
                        <label class="brxc-input__label has-tooltip m-top-16">Most used<div data-balloon="Most used classes on this page/post" data-balloon-pos="right"><i class="fas fa-circle-question"></i></div></label>
                        <div id="plainClassesMostUsedCanvas"></div>
                        <label class="brxc-input__label has-tooltip m-top-16">Search<div data-balloon="Search for classes" data-balloon-pos="right"><i class="fas fa-circle-question"></i></div></label>
                        <div id="plainClassesSearchWrapper">
                            <input type="text" class="" placeholder="Type your classname here to filter them">
                            <div id="plainClassesSearchOptionsCanvas"></div>
                        </div>
                        <div id="plainClassesSearchResultsCanvas"></div>
                        <!-- End of Panel Content -->
                    </div>
                    <!-- End of Modal Panel -->
                </div>
                <!-- End of Modal Panels Wrapper -->
            </div>
            <!-- End of Modal Container -->
             <!-- Modal Footer -->
             <div class="brxc-overlay__footer">
                <div class="brxc-overlay__footer-wrapper">
                    <a class="brxc-overlay__action-btn" style="margin-left:auto;" onclick="ADMINBRXC.resetClasses(this)"><span>Reset Classes</span></a>
                    <a class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.savePlainClasses(this, document.querySelector('#<?php echo esc_attr($overlay_id); ?> .CodeMirror').CodeMirror.getValue(CodeMirror));"><span>Update Classes</span></a>
                </div>
            </div>
        </div>
        <!-- End of Modal Wrapper -->
    </div>
    <!-- End of Main Inner -->
</div>
<!-- End of Main -->