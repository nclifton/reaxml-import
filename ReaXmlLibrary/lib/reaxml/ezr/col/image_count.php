<?php
class ReaxmlEzrColImage_count extends \ReaxmlEzrColumn {
	public function getValue() {
		if (! $this->isNew ()) {
			$count = $this->dbo->countEzrImagesUsingMls_id ( $this->getId () );
		} else {
			$count = 0;
		}
		if (isset ( $this->xml->objects->img )) {
			return max ( array (
					$count,
					count ( $this->xml->objects->img ) 
			) );
		} else {
			return $count;
		}
	}
}