<?php

namespace Skeletor\Admin;

use SilverStripe\Admin\ModelAdmin;
use Skeletor\DataObjects\FormSubmission;

class FormSubmissionAdmin extends ModelAdmin {

  private static $url_segment = 'form-submissions';
  private static $menu_title = 'Submissions';
  private static $menu_icon_class = 'font-icon-edit-list';

  private static $managed_models = [
    FormSubmission::class
  ];
}


