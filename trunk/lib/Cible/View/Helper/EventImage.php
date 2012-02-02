<?php
    class Cible_View_Helper_EventImage extends Cible_View_Helper_ModuleImage
    {
        public function eventImage($id, $image, $size, $options = null){

            return parent::moduleImage('event', $id, $image, $size, $options);
        }
    }