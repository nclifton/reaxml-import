<?php
class ReaxmlEzrColMediatype extends \ReaxmlEzrColumn {

	
	/* (non-PHPdoc)
	 * @see ReaxmlEzrColumn::getValue()
	 */
	public function getValue() {
		$mediaurl = new ReaxmlEzrColMediaurl($this->xml,$this->dbo,$this->configuration);
		if (strlen($mediaurl->getValue().'') == 0){
			return ($this->isNew()) ? 0 : null;
		} else {
			return ($this->isNew()) ? 1 : null;
		}
	}

	
	
}