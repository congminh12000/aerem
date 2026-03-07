<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcClassManagerOverlay';
$prefix_id = 'brxcClassManager';
$prefix_class = 'brxc-class-manager';
// Heading
$modal_heading_title = 'Class Manager';

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
                <!-- Modal Panel Switch -->
                <div class="brxc-overlay__panel-switcher-wrapper">
                    <!-- Label/Input Switchers -->
                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-class-manager" name="<?php echo esc_attr($prefix_id);?>-switch" class="brxc-input__radio" data-transform="0" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);ADMINBRXC.setClassManager();" checked>
                    <label for="<?php echo esc_attr($prefix_id);?>-class-manager" class="brxc-input__label">Overview</label>
                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-bulk-actions" name="<?php echo esc_attr($prefix_id);?>-switch" class="brxc-input__radio" data-transform="calc(-100% - 80px)" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);ADMINBRXC.setClassManagerBulk();">
                    <label for="<?php echo esc_attr($prefix_id);?>-bulk-actions" class="brxc-input__label">Bulk Actions</label>
                    <!-- End of Label/Input Switchers -->
                </div>
                <!-- End of Panel Switch -->
                <!-- Modal Panels Wrapper -->
                <div class="brxc-overlay__pannels-wrapper">
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                        <!-- Panel Content -->
                        <div class="brxc-class-manager__wrapper">
                            <div class="col-1">
                                <div class="brxc-overlay__search-box">
                                    <input type="search" class="class-filter" name="class-search" placeholder="Filter by name" data-type="title" onInput="ADMINBRXC.states.classManagerSearch = this.value;ADMINBRXC.setClassList('global');">
                                    <div class="iso-search-icon">
                                        <i class="bricks-svg ti-search"></i>
                                    </div>
                                    <div class="iso-reset light" style="right: 98px;" data-balloon="Reset Filter" data-balloon-pos="bottom-right" onclick="ADMINBRXC.resetFilter(this);">
                                        <i class="bricks-svg ti-close"></i>
                                    </div>
                                    <div class="iso-reset light" style="right: 68px;" data-balloon="Filter classes that contain styles" data-balloon-pos="bottom-right" onclick="ADMINBRXC.filterClassesByStyle(this);this.previousElementSibling.previousElementSibling.value = '';">
                                        <i class="bricks-svg fab fa-css3-alt"></i>
                                    </div>
                                    <div class="iso-reset light" style="right: 38px;" data-balloon="Filter by Active on page" data-balloon-pos="bottom-right" onclick="ADMINBRXC.filterClassesByActive(this);this.previousElementSibling.previousElementSibling.value = '';">
                                        <i class="bricks-svg fas fa-toggle-on"></i>
                                    </div>
                                    <div class="iso-reset light" data-balloon="Filter by Locked Status" data-balloon-pos="bottom-right" onclick="ADMINBRXC.filterClassesByStatus(this);this.previousElementSibling.previousElementSibling.value = '';">
                                        <i class="bricks-svg fas fa-lock"></i>
                                    </div>
                                </div>
                                <div class="cat-list">
                                    <div id="brxcCatListCanvas"></div>
                                </div>
                                <div class="class-list">
                                    <div id="brxcClassListCanvas"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div id="brxcClassContentCanvas"></div>
                            </div>
                        </div>
                        <!-- End of Panel Content -->
                    </div>
                    <!-- End of Modal Panel -->
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-2">
                        <!-- Panel Content -->
                        <div class="brxc-bulk-actions__wrapper">
                            <div class="col-left">
                                <div id="brxcClassBulkActionList"></div>
                            </div>
                            <div class="col-right">
                                <label class="has-tooltip">
                                    <span>I want to:</span>
                                    <div data-balloon="Select the action you want to apply to the global classes." data-balloon-pos="bottom" data-balloon-length="medium"><i class="fas fa-circle-question"></i></div>
                                </label>
                                <div class="brxc-overlay__panel-inline-btns-wrapper m-bottom-24">
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-rename" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="rename" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Rename';ADMINBRXC.setClassManagerBulk();" checked>
                                    <label for="<?php echo esc_attr($prefix_id);?>-rename" class="brxc-overlay__panel-inline-btns">Rename</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-duplicate" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="duplicate" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Duplicate';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-duplicate" class="brxc-overlay__panel-inline-btns">Duplicate</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-group" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="Group" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Group';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-group" class="brxc-overlay__panel-inline-btns">Group</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-lock" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="Lock" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Lock';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-lock" class="brxc-overlay__panel-inline-btns">Lock</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-unlock" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="Unlock" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Unlock';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-unlock" class="brxc-overlay__panel-inline-btns">Unlock</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-delete" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="delete" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Delete';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-delete" class="brxc-overlay__panel-inline-btns">Delete</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-export" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="export" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Export';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-export" class="brxc-overlay__panel-inline-btns">Export</label>
                                </div>
                                
                                <!-- Canvas -->
                                <div id="brxcClassBulkActionCanvas"></div>
                            </div>
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
                    <a class="brxc-overlay__action-btn primary" style="margin-left: auto;" onClick="ADMINBRXC.saveClassManager(this);"><span>Save Post</span></a>
                </div>
            </div>
        </div>
        <!-- End of Modal Wrapper -->
    </div>
    <!-- End of Main Inner -->
</div>
<!-- End of Main -->