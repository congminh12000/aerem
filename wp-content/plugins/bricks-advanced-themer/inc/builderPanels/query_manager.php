<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcQueryManagerOverlay';
$prefix_id = 'brxcQueryManager';
$prefix_class = 'brxc-query-manager';
// Heading
$modal_heading_title = 'Global Query Manager';

?>
<!-- Main -->
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onclick="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
    <!-- Main Inner -->
    <div class="brxc-overlay__inner brxc-large">
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
            </div>
            <!-- Modal Error Container for OpenAI -->
            <div class="brxc-overlay__error-message-wrapper"></div>
            <!-- Modal Container -->
            <div class="brxc-overlay__container">
                <!-- Modal Panels Wrapper -->
                <div class="brxc-overlay__pannels-wrapper">
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1" style="padding: 32px;">
                        <!-- Panel Content -->
                        <div id="queryManagerUI">
                            <div id="queryManagerUI__left">
                                <div class="brxc-overlay__search-box">
                                    <input type="search" class="class-filter" name="class-search" placeholder="Filter by name" data-type="title" oninput="ADMINBRXC.queryManagerStates.search = this.value;ADMINBRXC.queryManagerList();">
                                    <div class="iso-search-icon">
                                        <i class="bricks-svg ti-search"></i>
                                    </div>
                                    <div class="iso-reset light" data-balloon="Reset Filter" data-balloon-pos="bottom-right" onclick="ADMINBRXC.resetQueryFilter(this);">
                                        <i class="bricks-svg ti-close"></i>
                                    </div>
                                </div>
                                <div class="queryManagerUI__cat">
                                    <div id="brxcQueryCatListCanvas"></div>
                                </div>
                                <div class="queryManagerUI__querys">
                                    <ul id="queryManagerUI__list"></ul>
                                    <div class="brxc-class-manager__footer"><input type="text" id="addNewQueryLoop" placeholder="Add a new Query Loop" onkeyup="ADMINBRXC.addNewQueryLoop(event);"></div>
                                </div>
                            </div>
                            <div id="queryManagerUI__panel"></div>
                        </div>
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
                    <a class="brxc-overlay__action-btn secondary" style="margin-left: auto;" onClick="ADMINBRXC.saveQueryManager()"><span>Save & Continue</span></a>
                    <a class="brxc-overlay__action-btn primary" onClick="ADMINBRXC.saveQueryManager();ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');"><span>Save & Close</span></a>
                </div>
            </div>
        </div>
        <!-- End of Modal Wrapper -->
    </div>
    <!-- End of Main Inner -->
</div>
<!-- End of Main -->