<?php

namespace Drupal\first_module\Service;

class CustomDateFormatterService {
    public function dateChange($timeStamp) {
        return date('d-m-Y H:i:s', $timeStamp);
    }
}